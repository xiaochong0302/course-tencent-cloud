<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Course as CourseRepo;
use App\Repos\Topic as TopicRepo;

class CourseTopicList extends Builder
{

    public function handleCourses($relations)
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']] ?? null;
        }

        return $relations;
    }

    public function handleTopics($relations)
    {
        $topics = $this->getTopics($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['topic'] = $topics[$value['topic_id']] ?? null;
        }

        return $relations;
    }

    public function getCourses($relations)
    {
        $ids = kg_array_column($relations, 'course_id');

        $courseRepo = new CourseRepo();

        $columns = [
            'id', 'title', 'cover',
            'market_price', 'vip_price',
            'rating', 'model', 'level', 'attrs',
            'user_count', 'lesson_count', 'review_count', 'favorite_count',
        ];

        $courses = $courseRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($courses->toArray() as $course) {
            $course['cover'] = $baseUrl . $course['cover'];
            $course['attrs'] = json_decode($course['attrs'], true);
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getTopics($relations)
    {
        $ids = kg_array_column($relations, 'topic_id');

        $topicRepo = new TopicRepo();

        $topics = $topicRepo->findByIds($ids, ['id', 'title', 'summary']);

        $result = [];

        foreach ($topics->toArray() as $topic) {
            $result[$topic['id']] = $topic;
        }

        return $result;
    }

}
