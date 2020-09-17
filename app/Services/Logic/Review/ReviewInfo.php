<?php

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service;

class ReviewInfo extends Service
{

    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        return $this->handleReview($review);
    }

    protected function handleReview(ReviewModel $review)
    {
        $result = [
            'id' => $review->id,
            'content' => $review->content,
            'reply' => $review->reply,
            'rating' => $review->rating,
            'rating1' => $review->rating1,
            'rating2' => $review->rating2,
            'rating3' => $review->rating3,
            'like_count' => $review->like_count,
            'create_time' => $review->create_time,
            'update_time' => $review->update_time,
        ];

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($review->owner_id);

        $result['owner'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        return $result;
    }

}
