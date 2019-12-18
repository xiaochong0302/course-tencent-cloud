<?php

namespace App\Http\Home\Services;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Repos\CourseStudent as CourseUserRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\User as UserRepo;
use App\Transformers\ChapterList as ChapterListTransformer;
use App\Transformers\CommentList as CommentListTransformer;
use App\Transformers\ConsultList as ConsultListTransformer;
use App\Transformers\CourseUserList as CourseUserListTransformer;
use App\Transformers\ReviewList as ReviewListTransformer;

class Course extends Service
{

    public function getCategoryPaths($categoryId)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($categoryId);

        $ids = explode(',', trim($category->path, ','));

        $categories = $categoryRepo->findByIds($ids, ['id', 'name']);

        $paths = [];

        foreach ($categories as $category) {
            $paths[] = $category->name;
        }

        return implode(' > ', $paths);
    }

    public function getCourse($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getCurrentUser();

        return $this->handleCourse($user, $course);
    }

    public function getChapters($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getCurrentUser();

        $courseRepo = new CourseRepo();

        $topChapters = $courseRepo->findTopChapters($course->id);

        $subChapters = $courseRepo->findSubChapters($course->id, $course->model);

        $myChapters = null;

        if ($user->id > 0) {
            $myChapters = $courseRepo->findUserChapters($user->id, $course->id);
        }

        return $this->handleChapters($topChapters, $subChapters, $myChapters);
    }

    public function getConsults($id)
    {
        $course = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'course_id' => $course->id,
            'status' => ConsultModel::STATUS_NORMAL,
        ];

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($where, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    public function getReviews($id)
    {
        $course = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'course_id' => $course->id,
            'status' => ReviewModel::STATUS_NORMAL,
        ];

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($where, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    public function getComments($id)
    {
        $course = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'course_id' => $course->id,
            'status' => CommentModel::STATUS_NORMAL,
        ];

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($where, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

    public function getUsers($id)
    {
        $course = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = ['course_id' => $course->id];

        $courseUserRepo = new CourseUserRepo();

        $pager = $courseUserRepo->paginate($where, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    public function favorite($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $favoriteRepo = new CourseFavoriteRepo();

        $favorite = $favoriteRepo->find($user->id, $course->id);

        if ($favorite) {
            throw new BadRequestException('course.favorite_existed');
        }

        $favoriteRepo->create($user->id, $course->id);

        $course->favorite_count += 1;
        $course->update();
    }

    public function undoFavorite($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $favoriteRepo = new CourseFavoriteRepo();

        $favorite = $favoriteRepo->find($user->id, $course->id);

        if (!$favorite) {
            throw new BadRequestException('course.favorite_not_existed');
        }

        $favorite->delete();

        $course->favorite_count -= 1;
        $course->update();
    }

    public function apply($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        if ($course->status != CourseModel::STATUS_APPROVED) {
            throw new BadRequestException('course.apply_unpublished_course');
        }

        if ($course->price > 0) {
            throw new BadRequestException('course.apply_priced_course');
        }

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->find($user->id, $course->id);

        $expired = $courseUser->expire_time < time();

        if ($courseUser && !$expired) {
            throw new BadRequestException('course.has_applied');
        }

        $expireTime = time() + 86400 * $course->expiry;

        $courseUserRepo->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'expire_time' => $expireTime,
        ]);

        if (!$courseUser) {
            $course->user_count += 1;
            $course->update();
        }
    }

    private function findOrFail($id)
    {
        $repo = new CourseRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleConsults($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ConsultListTransformer();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleReviews($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReviewListTransformer();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleComments($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CommentListTransformer();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleUsers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseUserListTransformer();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleCourse($user, $course)
    {
        $result = $course->toArray();

        $userRepo = new UserRepo();

        $user = $userRepo->findShallowUser($course->user_id);

        $result['user'] = $user->toArray();

        $result['me']['favorited'] = 0;
        $result['me']['bought'] = 0;

        if ($user->id > 0) {

            $cfRepo = new CourseFavoriteRepo();

            $favorite = $cfRepo->find($user->id, $course->id);

            $result['me']['favorited'] = $favorite ? 1 : 0;

            $csRepo = new CourseUserRepo();

            $cs = $csRepo->find($user->id, $course->id);

            if ($cs && $cs->expire_time < time()) {
                $result['me']['bought'] = 1;
                $result['me']['finish_count'] = $cs->finish_count;
            }
        }

        return $result;
    }

    private function handleChapters($topChapters, $subChapters, $myChapters)
    {
        $chapters = array_merge($topChapters->toArray(), $subChapters->toArray());

        $studyHistory = [];

        if ($myChapters) {
            $studyHistory = $myChapters->toArray();
        }

        $builder = new ChapterListTransformer();

        $stepA = $builder->handleProcess($chapters, $studyHistory);

        $stepB = $builder->handleTree($stepA);

        $result = $builder->arrayToObject($stepB);

        return $result;
    }

}
