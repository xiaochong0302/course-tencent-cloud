<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\AnswerList as AnswerListBuilder;
use App\Builders\ArticleList as ArticleListBuilder;
use App\Builders\QuestionList as QuestionListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Answer as AnswerRepo;
use App\Repos\Article as ArticleRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\Question as QuestionRepo;

class Report extends Service
{

    public function getArticles()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $articleRepo = new ArticleRepo();

        $pager = $articleRepo->paginate($params, 'reported', $page, $limit);

        return $this->handleArticles($pager);
    }

    public function getQuestions()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $questionRepo = new QuestionRepo();

        $pager = $questionRepo->paginate($params, 'reported', $page, $limit);

        return $this->handleQuestions($pager);
    }

    public function getAnswers()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $answerRepo = new AnswerRepo();

        $pager = $answerRepo->paginate($params, 'reported', $page, $limit);

        return $this->handleAnswers($pager);
    }

    public function getComments()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, 'reported', $page, $limit);

        return $this->handleComments($pager);
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

    protected function handleAnswers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new AnswerListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleQuestions($items);
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    protected function handleComments($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new AnswerListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->items = $pipeB;
        }

        return $pager;
    }

}
