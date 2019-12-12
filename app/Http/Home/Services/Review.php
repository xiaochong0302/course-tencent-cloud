<?php

namespace App\Http\Home\Services;

use App\Models\Review as ReviewModel;
use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Validators\Review as ReviewFilter;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\ReviewStats as ReviewStatsRepo;
use App\Repos\ReviewVote as ReviewVoteRepo;
use App\Repos\User as UserRepo;

class Review extends Service
{
    
    public function create()
    {
        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ReviewFilter();

        $data = [];

        $data['user_id'] = $user->id;
        $data['course_id'] = $filter->checkCourseId($post['course_id']);
        $data['content'] = $filter->checkContent($post['content']);
        $data['rating'] = $filter->checkRating($post['rating']);
        
        $courseRepo = new CourseRepo(); 
        
        $course = $courseRepo->findById($data['course_id']);
        
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->find($user->id, $course->id);

        if (!$courseUser) {
            throw new ForbiddenException('course.has_not_applied');
        }

        $reviewRepo = new ReviewRepo();

        $record = $reviewRepo->findByUserCourseId($user->id, $course->id);

        if ($record) {
            throw new BadRequestException('course.has_reviewed');
        }

        $review = $reviewRepo->create($data);

        $courseUser->reviewed = 1;

        $courseUser->update();
        
        $course->review_count += 1;

        $course->update();

        $this->handleReviewStats($course, $review);

        return $review;
    }

    public function getReview($id)
    {
        $review = $this->findOrFail($id);

        return $this->handleReview($review);
    }

    public function update($id)
    {
        $review = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ReviewFilter();

        $filter->checkOwner($user->id, $review->user_id);

        $data = [];

        $data['content'] = $filter->checkContent($post['content']);
        $data['rating'] = $filter->checkRating($post['rating']);

        $review->update($data);

        return $review;
    }

    public function delete($id)
    {
        $review = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $filter = new ReviewFilter();

        $filter->checkOwner($user->id, $review->user_id);

        if ($review->status == ReviewModel::STATUS_DELETED) {
            return false;
        }

        $review->status = ReviewModel::STATUS_DELETED;

        $review->update();
    }

    public function agree($id)
    {
        $review = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new ReviewVoteRepo();

        $vote = $voteRepo->find($user->id, $review->id);

        if ($vote) {
            throw new BadRequestException('review.has_voted');
        }

        $voteRepo->agree($user->id, $review->id);

        $review->agree_count += 1;

        $review->update();
    }

    public function oppose($id)
    {
        $review = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new ReviewVoteRepo();

        $vote = $voteRepo->find($user->id, $review->id);

        if ($vote) {
            throw new BadRequestException('review.has_voted');
        }

        $voteRepo->oppose($user->id, $review->id);

        $review->oppose_count += 1;

        $review->update();
    }

    public function reply($id)
    {
        $review = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ReviewFilter();

        $filter->checkOwner($user->id, $review->user_id);

        $reply = $filter->checkReply($post['reply']);

        $review->reply = $reply;
        $review->reply_time = time();

        $review->update();
    }

    private function findOrFail($id)
    {
        $repo = new ReviewRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleReview($review)
    {
        $result = $review->toArray();

        $userRepo = new UserRepo();

        $user = $userRepo->findShallowUser($review->user_id);

        $result['user'] = $user->toArray();

        return (object) $result;
    }
    
    private function handleReviewStats($course, $review)
    {
        $statsRepo = new ReviewStatsRepo();

        $stats = $statsRepo->findByCourseId($course->id);

        if ($stats) {

            $stats->total_count += 1;

            if (in_array($review->rating, [1, 2])) {
                $stats->bad_count += 1;
            } elseif (in_array($review->rating, [3, 4])) {
                $stats->medium_count += 1;
            } else {
                $stats->good_count += 1;
            }

            $stats->update();
            
        } else {
            
            $data = [];

            $data['course_id'] = $course->id;
            $data['total_count'] = 1;

            if (in_array($review->rating, [1, 2])) {
                $data['bad_count'] = 1;
            } else if (in_array($review->rating, [3, 4])) {
                $data['medium_count'] = 1;
            } else {
                $data['good_count'] = 1;
            }

            $statsRepo->create($data);
        }
    }

}
