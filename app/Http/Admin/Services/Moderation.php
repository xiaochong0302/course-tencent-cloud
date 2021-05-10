<?php

namespace App\Http\Admin\Services;

use App\Builders\ArticleList as ArticleListBuilder;
use App\Builders\QuestionList as QuestionListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Article as ArticleModel;
use App\Models\Question as QuestionModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\Question as QuestionRepo;

class Moderation extends Service
{

    public function getArticles()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = ArticleModel::PUBLISH_PENDING;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $articleRepo = new ArticleRepo();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleArticles($pager);
    }

    public function getQuestions()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = QuestionModel::PUBLISH_PENDING;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $questionRepo = new QuestionRepo();

        $pager = $questionRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($pager);
    }

    protected function handleArticles($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ArticleListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleArticles($items);
            $pipeB = $builder->handleCategories($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

    protected function handleQuestions($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new QuestionListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleQuestions($items);
            $pipeB = $builder->handleCategories($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
