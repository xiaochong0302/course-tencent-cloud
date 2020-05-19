<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Topic as TopicRepo;

class TopicCourseList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "topic_course_list:{$id}";
    }

    public function getContent($id = null)
    {
        $topicRepo = new TopicRepo();

        $courses = $topicRepo->findCourses($id);

        if ($courses->count() == 0) {
            return [];
        }

        return $this->handleContent($courses);
    }

    /**
     * @param CourseModel[] $courses
     * @return array
     */
    public function handleContent($courses)
    {
        $result = [];

        foreach ($courses as $course) {
            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
            ];
        }

        return $result;
    }

}
