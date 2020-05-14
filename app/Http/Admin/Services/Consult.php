<?php

namespace App\Http\Admin\Services;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Validators\Consult as ConsultValidator;

class Consult extends Service
{

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

    public function updateConsult($id)
    {
        $consult = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new ConsultValidator();

        $data = [];

        if (isset($post['question'])) {
            $data['question'] = $validator->checkQuestion($post['question']);
        }

        if (isset($post['answer'])) {
            $data['answer'] = $validator->checkAnswer($post['answer']);
        }

        if (isset($post['private'])) {
            $data['private'] = $validator->checkPrivateStatus($post['private']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $consult->update($data);

        return $consult;
    }

    public function deleteConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 1;

        $consult->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($consult->course_id);

        $course->consult_count -= 1;

        $course->update();
    }

    public function restoreConsult($id)
    {
        $consult = $this->findOrFail($id);

        $consult->deleted = 0;

        $consult->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($consult->course_id);

        $course->consult_count += 1;

        $course->update();
    }

    protected function findOrFail($id)
    {
        $validator = new ConsultValidator();

        return $validator->checkConsult($id);
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
