<?php

namespace App\Http\Home\Services;

use App\Builders\ChapterList as ChapterTreeBuilder;
use App\Builders\CourseList as CourseListBuilder;
use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseFavorite as FavoriteModel;
use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseFavorite as FavoriteRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Review as ReviewRepo;
use App\Validators\Course as CourseValidator;
use App\Validators\Review as ReviewValidator;

class Course extends Service
{

    public function getCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        if (!empty($params['category_id'])) {
            $params['category_id'] = $this->getChildCategoryIds($params['category_id']);
        }

        $params['published'] = 1;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();
        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    public function getCourse($id)
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getCurrentUser();

        return $this->handleCourse($course, $user);
    }

    public function getChapters($id)
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getCurrentUser();

        $courseRepo = new CourseRepo();

        $chapters = $courseRepo->findChapters($course->id);

        if ($chapters->count() == 0) {
            return [];
        }

        return $this->handleChapters($chapters, $course, $user);
    }

    public function getReviews($id)
    {
        $course = $this->checkCourseCache($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'course_id' => $course->id,
            'published' => 1,
            'deleted' => 0,
        ];

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($where, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    public function reviewCourse($id)
    {
        $post = $this->request->getPost();

        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $rating = $validator->checkRating($post['rating']);
        $content = $validator->checkContent($post['content']);

        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findReview($course->id, $user->id);

        if (!$review) {
            $review = new ReviewModel();
            $review->course_id = $course->id;
            $review->user_id = $user->id;
            $review->rating = $rating;
            $review->content = $content;
            $review->create();

            $course->review_count += 1;
            $course->update();
        } else {
            $review->rating = $rating;
            $review->content = $content;
            $review->update();
        }
    }

    public function favoriteCourse($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $favoriteRepo = new FavoriteRepo();

        $favorite = $favoriteRepo->findFavorite($course->id, $user->id);

        if (!$favorite) {

            $favorite = new FavoriteModel();
            $favorite->course_id = $course->id;
            $favorite->user_id = $user->id;
            $favorite->create();

            $course->favorite_count += 1;
            $course->update();

        } else {

            if ($favorite->deleted == 0) {
                $favorite->deleted = 1;
                $course->favorite_count -= 1;
            } else {
                $favorite->deleted = 0;
                $course->favorite_count += 1;
            }

            $favorite->update();
            $course->update();
        }
    }

    protected function checkCourse($id)
    {
        $validator = new CourseValidator();

        $course = $validator->checkCourse($id);

        return $course;
    }

    protected function checkCourseCache($id)
    {
        $validator = new CourseValidator();

        $course = $validator->checkCourseCache($id);

        return $course;
    }

    protected function getChildCategoryIds($id)
    {
        $categoryService = new \App\Services\Category();

        $childIds = $categoryService->getChildIds($id);

        return $childIds;
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleCategories($pipeB);
            $pipeD = $builder->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

    protected function handleReviews($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    /**
     * @param \App\Models\Course $course
     * @param \App\Models\User $user
     * @return object
     */
    protected function handleCourse($course, $user)
    {
        $result = $course->toArray();

        $me = [
            'reviewed' => 0,
            'favorited' => 0,
            'bought' => 0,
            'progress' => 0,
        ];

        if (!empty($user->id)) {

            $favoriteRepo = new FavoriteRepo();

            $favorite = $favoriteRepo->findFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            $courseUserRepo = new CourseUserRepo();

            $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);

            if ($courseUser) {
                $me['reviewed'] = $courseUser->reviewed;
                $me['progress'] = $courseUser->progress;
                if ($courseUser->expire_time < time()) {
                    $me['bought'] = 1;
                }
            }
        }

        $result['me'] = $me;

        return kg_array_object($result);
    }

    /**
     * @param array $chapters
     * @param \App\Models\Course $course
     * @param \App\Models\User $user
     * @return object
     */
    protected function handleChapters($chapters, $course, $user)
    {
        $chapterList = $chapters->toArray();

        $studyList = [];

        if (!empty($user->id)) {
            $courseRepo = new CourseRepo();
            $userChapters = $courseRepo->findUserChapters($course->id, $user->id);
            $studyList = $userChapters->toArray();
        }

        $builder = new ChapterTreeBuilder();

        $stepA = $builder->handleProcess($chapterList, $studyList);

        $stepB = $builder->handleTree($stepA);

        $stepC = $builder->arrayToObject($stepB);

        return $stepC;
    }

}
