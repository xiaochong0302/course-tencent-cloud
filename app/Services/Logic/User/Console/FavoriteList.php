<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Builders\ArticleFavoriteList as ArticleFavoriteListBuilder;
use App\Builders\CourseFavoriteList as CourseFavoriteListBuilder;
use App\Builders\ExamPaperFavoriteList as ExamPaperFavoriteListBuilder;
use App\Builders\QuestionFavoriteList as QuestionFavoriteListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ArticleFavorite as ArticleFavoriteRepo;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Repos\ExamPaperFavorite as ExamPaperFavoriteRepo;
use App\Repos\QuestionFavorite as QuestionFavoriteRepo;
use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class FavoriteList extends LogicService
{

    public function handle(): ?PagerRepoInterface
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

        $result = null;

        if ($type == 'course') {

            $favoriteRepo = new CourseFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            $result = $this->handleCourses($pager);

        } elseif ($type == 'article') {

            $favoriteRepo = new ArticleFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            $result = $this->handleArticles($pager);

        } elseif ($type == 'question') {

            $favoriteRepo = new QuestionFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            $result = $this->handleQuestions($pager);

        } elseif ($type == 'exam_paper') {

            $favoriteRepo = new ExamPaperFavoriteRepo();

            $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

            $result = $this->handleExamPapers($pager);
        }

        return $result;
    }

    protected function handleCourses(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new CourseFavoriteListBuilder();

        $relations = $pager->getItems()->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as $relation) {
            $course = $courses[$relation['course_id']] ?? null;
            $items[] = $course;
        }

        $pager->setItems($items);

        return $pager;
    }

    protected function handleArticles(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new ArticleFavoriteListBuilder();

        $relations = $pager->getItems()->toArray();

        $articles = $builder->getArticles($relations);

        $items = [];

        foreach ($relations as $relation) {
            $article = $articles[$relation['article_id']] ?? null;
            $items[] = $article;
        }

        $pager->setItems($items);

        return $pager;
    }

    protected function handleQuestions(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new QuestionFavoriteListBuilder();

        $relations = $pager->getItems()->toArray();

        $questions = $builder->getQuestions($relations);

        $items = [];

        foreach ($relations as $relation) {
            $question = $questions[$relation['question_id']] ?? null;
            $items[] = $question;
        }

        $pager->setItems($items);

        return $pager;
    }

    protected function handleExamPapers(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() == 0) {
            return $pager;
        }

        $builder = new ExamPaperFavoriteListBuilder();

        $relations = $pager->getItems()->toArray();

        $papers = $builder->getExamPapers($relations);

        $items = [];

        foreach ($relations as $relation) {
            $paper = $papers[$relation['paper_id']] ?? null;
            $items[] = $paper;
        }

        $pager->setItems($items);

        return $pager;
    }

}
