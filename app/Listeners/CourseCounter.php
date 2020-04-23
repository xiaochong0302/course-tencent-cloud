<?php

namespace App\Listeners;

use App\Caches\CourseCounter as CacheCourseCounter;
use App\Models\Course as CourseModel;
use App\Services\Syncer\CourseCounter as CourseCounterSyncer;
use App\Services\Syncer\CourseIndex as CourseIndexSyncer;
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

        $this->syncCourseCounter($course);

        $this->syncCourseIndex($course);
    }

    public function decrUserCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'user_count');

        $this->syncCourseCounter($course);

        $this->syncCourseIndex($course);
    }

    public function incrCommentCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'comment_count');

        $this->syncCourseCounter($course);
    }

    public function decrCommentCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'comment_count');

        $this->syncCourseCounter($course);
    }

    public function incrConsultCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'consult_count');

        $this->syncCourseCounter($course);
    }

    public function decrConsultCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'consult_count');

        $this->syncCourseCounter($course);
    }

    public function incrReviewCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'review_count');

        $this->syncCourseCounter($course);
    }

    public function decrReviewCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'review_count');

        $this->syncCourseCounter($course);
    }

    public function incrFavoriteCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'favorite_count');

        $this->syncCourseCounter($course);
    }

    public function decrFavoriteCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'favorite_count');

        $this->syncCourseCounter($course);
    }

    public function incrLessonCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hIncrBy($course->id, 'lesson_count');

        $this->syncCourseCounter($course);
    }

    public function decrLessonCount(Event $event, $source, CourseModel $course)
    {
        $this->counter->hDecrBy($course->id, 'lesson_count');

        $this->syncCourseCounter($course);
    }

    protected function syncCourseCounter(CourseModel $course)
    {
        $syncer = new CourseCounterSyncer();

        $syncer->addItem($course->id);
    }

    protected function syncCourseIndex(CourseModel $course)
    {
        $syncer = new CourseIndexSyncer();

        $syncer->addItem($course->id);
    }

}