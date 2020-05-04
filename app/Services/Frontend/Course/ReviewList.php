<?php

namespace App\Services\Frontend\Course;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\ReviewVote as ReviewVoteModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class ReviewList extends Service
{

    /**
     * @var CourseModel
     */
    protected $course;

    /**
     * @var UserModel
     */
    protected $user;

    use CourseTrait;

    public function handle($id)
    {
        $this->course = $this->checkCourse($id);

        $this->user = $this->getCurrentUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $this->course->id,
            'published' => 1,
            'deleted' => 0,
        ];

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    protected function handleReviews($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ReviewListBuilder();

        $reviews = $pager->items->toArray();

        $users = $builder->getUsers($reviews);

        $votes = $this->getReviewVotes($this->course->id, $this->user->id);

        $items = [];

        foreach ($reviews as $review) {

            $user = $users[$review['user_id']] ?? [];

            $me = [
                'agreed' => $votes[$review['id']]['agreed'] ?? 0,
                'opposed' => $votes[$review['id']]['opposed'] ?? 0,
            ];

            $items[] = [
                'id' => $review['id'],
                'rating' => $review['rating'],
                'content' => $review['content'],
                'agree_count' => $review['agree_count'],
                'oppose_count' => $review['oppose_count'],
                'create_time' => $review['create_time'],
                'user' => $user,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getReviewVotes($courseId, $userId)
    {
        if (!$courseId || !$userId) {
            return [];
        }

        $courseRepo = new CourseRepo();

        $votes = $courseRepo->findUserReviewVotes($courseId, $userId);

        if ($votes->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($votes as $vote) {
            $result[$vote->review_id] = [
                'agreed' => $vote->type == ReviewVoteModel::TYPE_AGREE ? 1 : 0,
                'opposed' => $vote->type == ReviewVoteModel::TYPE_OPPOSE ? 1 : 0,
            ];
        }

        return $result;
    }

}
