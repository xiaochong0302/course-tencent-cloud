<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Chapter as ChapterModel;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\Reason as ReasonModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Logic\Notice\External\ConsultReply as ConsultReplyNotice;
use App\Validators\Consult as ConsultValidator;

class Consult extends Service
{

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

        $params['deleted'] = $params['deleted'] ?? 0;

        $accountRepo = new AccountRepo();

        /**
         * 兼容用户编号｜手机号码｜邮箱地址查询
         */
        if (!empty($params['owner_id'])) {
            if (CommonValidator::phone($params['owner_id'])) {
                $account = $accountRepo->findByPhone($params['owner_id']);
                $params['owner_id'] = $account ? $account->id : -1000;
            } elseif (CommonValidator::email($params['owner_id'])) {
                $account = $accountRepo->findByEmail($params['owner_id']);
                $params['owner_id'] = $account ? $account->id : -1000;
            }
        }

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

        $this->recountItemConsults($consult);

        $this->eventsManager->fire('Consult:afterUpdate', $this, $consult);

        return $consult;
    }

    public function deleteConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 1;

        $consult->update();

        $this->recountItemConsults($consult);

        $sender = $this->getLoginUser();

        $this->handleConsultDeletedNotice($consult, $sender);

        $this->eventsManager->fire('Consult:afterDelete', $this, $consult);
    }

    public function restoreConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 0;

        $consult->update();

        $this->recountItemConsults($consult);

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

        $this->recountItemConsults($consult);

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

            $this->recountItemConsults($consult);
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
            $this->recountItemConsults($consult);
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

    protected function findChapter($id)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findById($id);
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

    protected function recountItemConsults(ConsultModel $consult)
    {
        if ($consult->course_id > 0) {
            $course = $this->findCourse($consult->course_id);
            $this->recountCourseConsults($course);
        }

        if ($consult->chapter_id > 0) {
            $chapter = $this->findChapter($consult->chapter_id);
            $this->recountChapterConsults($chapter);
        }
    }

    protected function recountCourseConsults(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $consultCount = $courseRepo->countConsults($course->id);

        $course->consult_count = $consultCount;

        $course->update();
    }

    protected function recountChapterConsults(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $consultCount = $chapterRepo->countConsults($chapter->id);

        $chapter->consult_count = $consultCount;

        $chapter->update();
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
