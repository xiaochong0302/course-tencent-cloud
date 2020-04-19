<?php

namespace App\Listeners;

use App\Caches\CourseCounter as CacheCourseCounter;
use App\Models\User as CourseModel;
use Phalcon\Events\Event;

class CourseCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheCourseCounter();
    }

    public function incrUserCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'user_count');
    }

    public function decrUserCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'user_count');
    }

    public function incrCommentCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'comment_count');
    }

    public function decrCommentCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'comment_count');
    }

    public function incrConsultCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'consult_count');
    }

    public function decrConsultCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'consult_count');
    }

    public function incrReviewCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'review_count');
    }

    public function decrReviewCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'review_count');
    }

    public function incrFavoriteCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'favorite_count');
    }

    public function decrFavoriteCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'favorite_count');
    }

    public function incrLessonCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'lesson_count');
    }

    public function decrLessonCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'lesson_count');
    }

}