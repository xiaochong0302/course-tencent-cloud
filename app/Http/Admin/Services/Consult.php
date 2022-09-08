<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Chapter as ChapterModel;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
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

    public function getConsults()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

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
            $this->handleItemConsults($consult);
        }

        $consult->update($data);

        if ($firstReply) {
            $this->handleReplyNotice($consult);
        }

        return $consult;
    }

    public function deleteConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 1;

        $consult->update();

        $this->handleItemConsults($consult);
    }

    public function restoreConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 0;

        $consult->update();

        $this->handleItemConsults($consult);
    }

    public function moderate($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);

        $consult = $this->findOrFail($id);

        if ($type == 'approve') {
            $consult->published = ConsultModel::PUBLISH_APPROVED;
        } elseif ($type == 'reject') {
            $consult->published = ConsultModel::PUBLISH_REJECTED;
        }

        $consult->update();

        $this->handleItemConsults($consult);

        if ($type == 'approve') {
            $this->eventsManager->fire('Consult:afterApprove', $this, $consult);
        } elseif ($type == 'reject') {
            $this->eventsManager->fire('Consult:afterReject', $this, $consult);
        }

        return $consult;
    }

    protected function handleItemConsults(ConsultModel $consult)
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

    protected function handleReplyNotice(ConsultModel $consult)
    {
        $notice = new ConsultReplyNotice();

        $notice->createTask($consult);
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
