<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\Reason as ReasonModel;
use App\Models\User as UserModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Logic\Notice\External\ConsultReply as ConsultReplyNotice;
use App\Validators\Consult as ConsultValidator;

class Consult extends Service
{

    use AccountSearchTrait;

    public function getPublishTypes()
    {
        return ConsultModel::publishTypes();
    }

    public function getReasons()
    {
        return ReasonModel::consultRejectOptions();
    }

    public function getXmCourses()
    {
        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $item->market_price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getConsults()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    public function getCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($courseId);
    }

    public function getConsult($id)
    {
        return $this->findOrFail($id);
    }

    public function getConsultInfo($id)
    {
        $service = new ConsultInfoService();

        return $service->handle($id);
    }

    public function updateConsult($id)
    {
        $consult = $this->findOrFail($id);

        $course = $this->findCourse($consult->course_id);

        $post = $this->request->getPost();

        $validator = new ConsultValidator();

        $data = [];

        $firstReply = false;

        if (!empty($post['question'])) {
            $data['question'] = $validator->checkQuestion($post['question']);
        }

        if (!empty($post['answer'])) {
            $data['answer'] = $validator->checkAnswer($post['answer']);
            $data['reply_time'] = time();
            if ($consult->reply_time == 0) {
                $firstReply = true;
            }
        }

        if (isset($post['private'])) {
            $data['private'] = $validator->checkPrivateStatus($post['private']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $consult->update($data);

        if ($firstReply) {
            $this->handleReplyNotice($consult);
        }

        $this->recountCourseConsults($course);

        $this->eventsManager->fire('Consult:afterUpdate', $this, $consult);

        return $consult;
    }

    public function deleteConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 1;

        $consult->update();

        $course = $this->findCourse($consult->course_id);

        $this->recountCourseConsults($course);

        $sender = $this->getLoginUser();

        $this->handleConsultDeletedNotice($consult, $sender);

        $this->eventsManager->fire('Consult:afterDelete', $this, $consult);
    }

    public function restoreConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 0;

        $consult->update();

        $course = $this->findCourse($consult->course_id);

        $this->recountCourseConsults($course);

        $this->eventsManager->fire('Consult:afterRestore', $this, $consult);
    }

    public function moderate($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $consult = $this->findOrFail($id);
        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $consult->published = ConsultModel::PUBLISH_APPROVED;
            $consult->update();

            $this->handleConsultApprovedNotice($consult, $sender);

            $this->eventsManager->fire('Consult:afterApprove', $this, $consult);

        } elseif ($type == 'reject') {

            $consult->published = ConsultModel::PUBLISH_REJECTED;
            $consult->update();

            $this->handleConsultRejectedNotice($consult, $sender, $reason);

            $this->eventsManager->fire('Consult:afterReject', $this, $consult);
        }

        $course = $this->findCourse($consult->course_id);

        $this->recountCourseConsults($course);

        return $consult;
    }

    public function batchModerate()
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $consultRepo = new ConsultRepo();

        $consults = $consultRepo->findByIds($ids);

        if ($consults->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($consults as $consult) {

            if ($type == 'approve') {

                $consult->published = ConsultModel::PUBLISH_APPROVED;
                $consult->update();

                $this->handleConsultApprovedNotice($consult, $sender);

            } elseif ($type == 'reject') {

                $consult->published = ConsultModel::PUBLISH_REJECTED;
                $consult->update();

                $this->handleConsultRejectedNotice($consult, $sender);
            }

            $course = $this->findCourse($consult->course_id);

            $this->recountCourseConsults($course);
        }
    }

    public function batchDelete()
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $consultRepo = new ConsultRepo();

        $consults = $consultRepo->findByIds($ids);

        if ($consults->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($consults as $consult) {

            $consult->deleted = 1;
            $consult->update();

            $this->handleConsultDeletedNotice($consult, $sender);

            $course = $this->findCourse($consult->course_id);

            $this->recountCourseConsults($course);
        }
    }

    protected function findOrFail($id)
    {
        $validator = new ConsultValidator();

        return $validator->checkConsult($id);
    }

    protected function findCourse($id)
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($id);
    }

    protected function handleReplyNotice(ConsultModel $consult)
    {
        $notice = new ConsultReplyNotice();

        $notice->createTask($consult);
    }

    protected function handleConsultApprovedNotice(ConsultModel $review, UserModel $sender)
    {

    }

    protected function handleConsultRejectedNotice(ConsultModel $review, UserModel $sender, $reason = '')
    {

    }

    protected function handleConsultDeletedNotice(ConsultModel $review, UserModel $sender, $reason = '')
    {

    }

    protected function recountCourseConsults(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $consultCount = $courseRepo->countConsults($course->id);

        $course->consult_count = $consultCount;

        $course->update();
    }

    protected function handleConsults($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ConsultListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
