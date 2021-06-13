<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Review as ReviewRepo;
use App\Repos\ReviewLike as ReviewLikeRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ReviewList extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $course->id,
            'published' => 1,
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

        $meMappings = $this->getMeMappings($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $owner = $users[$review['owner_id']] ?? new \stdClass();
            $me = $meMappings[$review['id']];

            $items[] = [
                'id' => $review['id'],
                'rating' => $review['rating'],
                'content' => $review['content'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getMeMappings($consults)
    {
        $user = $this->getCurrentUser(true);

        $likeRepo = new ReviewLikeRepo();

        $likedIds = [];

        if ($user->id > 0) {
            $likes = $likeRepo->findByUserId($user->id);
            $likedIds = array_column($likes->toArray(), 'review_id');
        }

        $result = [];

        foreach ($consults as $consult) {
            $result[$consult['id']] = [
                'liked' => in_array($consult['id'], $likedIds) ? 1 : 0,
            ];
        }

        return $result;
    }

}
