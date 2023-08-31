<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Builders\ArticleFavoriteList as ArticleFavoriteListBuilder;
use App\Builders\CourseFavoriteList as CourseFavoriteListBuilder;
use App\Builders\QuestionFavoriteList as QuestionFavoriteListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ArticleFavorite as ArticleFavoriteRepo;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Repos\QuestionFavorite as QuestionFavoriteRepo;
use App\Services\Logic\Service as LogicService;

class FavoriteList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $type = $params['type'] ?? 'course';

        $params['user_id'] = $user->id;

        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        if ($type == 'course') {

            $favoriteRepo = new CourseFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            return $this->handleCourses($pager);

        } elseif ($type == 'article') {

            $favoriteRepo = new ArticleFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            return $this->handleArticles($pager);

        } elseif ($type == 'question') {

            $favoriteRepo = new QuestionFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            return $this->handleQuestions($pager);
        }

        return null;
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new CourseFavoriteListBuilder();

        $relations = $pager->items->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as $relation) {
            $course = $courses[$relation['course_id']] ?? new \stdClass();
            $items[] = $course;
        }

        $pager->items = $items;

        return $pager;
    }

    protected function handleArticles($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ArticleFavoriteListBuilder();

        $relations = $pager->items->toArray();

        $articles = $builder->getArticles($relations);

        $items = [];

        foreach ($relations as $relation) {
            $article = $articles[$relation['article_id']] ?? new \stdClass();
            $items[] = $article;
        }

        $pager->items = $items;

        return $pager;
    }

    protected function handleQuestions($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new QuestionFavoriteListBuilder();

        $relations = $pager->items->toArray();

        $questions = $builder->getQuestions($relations);

        $items = [];

        foreach ($relations as $relation) {
            $question = $questions[$relation['question_id']] ?? new \stdClass();
            $items[] = $question;
        }

        $pager->items = $items;

        return $pager;
    }

}
