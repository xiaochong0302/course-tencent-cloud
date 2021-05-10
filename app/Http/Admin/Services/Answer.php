<?php

namespace App\Http\Admin\Services;

use App\Builders\AnswerList as AnswerListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Answer as AnswerRepo;
use App\Validators\Answer as AnswerValidator;

class Answer extends Service
{

    public function getAnswers()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $answerRepo = new AnswerRepo();

        $pager = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($pager);
    }

    public function getAnswer($id)
    {
        return $this->findOrFail($id);
    }

    public function updateAnswer($id)
    {
        $answer = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new AnswerValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $answer->update($data);

        return $answer;
    }

    public function deleteAnswer($id)
    {
        $page = $this->findOrFail($id);

        $page->deleted = 1;

        $page->update();

        return $page;
    }

    public function restoreAnswer($id)
    {
        $page = $this->findOrFail($id);

        $page->deleted = 0;

        $page->update();

        return $page;
    }

    protected function findOrFail($id)
    {
        $validator = new AnswerValidator();

        return $validator->checkAnswer($id);
    }

    protected function handleAnswers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new AnswerListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
