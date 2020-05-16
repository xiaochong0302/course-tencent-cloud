<?php

namespace App\Services\Frontend\Review;

use App\Models\Review as ReviewModel;
use App\Models\ReviewVote as ReviewVoteModel;
use App\Models\User as UserModel;
use App\Repos\ReviewVote as ReviewVoteRepo;
use App\Repos\User as UserRepo;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service as FrontendService;

class ReviewInfo extends FrontendService
{

    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getCurrentUser();

        return $this->handleReview($review, $user);
    }

    protected function handleReview(ReviewModel $review, UserModel $user)
    {
        $result = [
            'id' => $review->id,
            'content' => $review->content,
            'reply' => $review->reply,
            'rating' => $review->rating,
            'agree_count' => $review->agree_count,
            'oppose_count' => $review->oppose_count,
            'create_time' => $review->create_time,
            'update_time' => $review->update_time,
        ];

        $me = [
            'agreed' => 0,
            'opposed' => 0,
        ];

        if ($user->id > 0) {

            $voteRepo = new ReviewVoteRepo();

            $vote = $voteRepo->findReviewVote($review->id, $user->id);

            if ($vote) {
                $me['agreed'] = $vote->type == ReviewVoteModel::TYPE_AGREE ? 1 : 0;
                $me['opposed'] = $vote->type == ReviewVoteModel::TYPE_OPPOSE ? 1 : 0;
            }
        }

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($review->user_id);

        $result['owner'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        $result['me'] = $me;

        return $result;
    }

}
