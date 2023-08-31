<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\Topic as TopicCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseTopic as CourseTopicModel;
use App\Models\Topic as TopicModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseTopic as CourseTopicRepo;
use App\Repos\Topic as TopicRepo;
use App\Validators\Topic as TopicValidator;

class Topic extends Service
{

    public function getXmCourses($id)
    {
        $topicRepo = new TopicRepo();

        $courses = $topicRepo->findCourses($id);

        $courseIds = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $courseIds[] = $course->id;
            }
        }

        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $price = $item->market_price > 0 ? sprintf("￥%0.2f", $item->market_price) : '免费';
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $price),
                'value' => $item->id,
                'selected' => in_array($item->id, $courseIds),
            ];
        }

        return $result;
    }

    public function getTopics()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $topicRepo = new TopicRepo();

        return $topicRepo->paginate($params, $sort, $page, $limit);
    }

    public function getTopic($id)
    {
        return $this->findOrFail($id);
    }

    public function createTopic()
    {
        $post = $this->request->getPost();

        $validator = new TopicValidator();

        $data = [];

        $data['title'] = $validator->checkTitle($post['title']);

        $topic = new TopicModel();

        $topic->create($data);

        $this->rebuildTopicCache($topic);

        return $topic;
    }

    public function updateTopic($id)
    {
        $topic = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new TopicValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['xm_course_ids'])) {
            $this->saveCourses($topic, $post['xm_course_ids']);
        }

        $topic->update($data);

        $this->updateCourseCount($topic);

        $this->rebuildTopicCache($topic);

        return $topic;
    }

    public function deleteTopic($id)
    {
        $topic = $this->findOrFail($id);

        $topic->deleted = 1;

        $topic->update();

        $this->rebuildTopicCache($topic);

        return $topic;
    }

    public function restoreTopic($id)
    {
        $topic = $this->findOrFail($id);

        $topic->deleted = 0;

        $topic->update();

        $this->rebuildTopicCache($topic);

        return $topic;
    }

    protected function findOrFail($id)
    {
        $validator = new TopicValidator();

        return $validator->checkTopic($id);
    }

    protected function saveCourses(TopicModel $topic, $courseIds)
    {
        $topicRepo = new TopicRepo();

        $courses = $topicRepo->findCourses($topic->id);

        $originCourseIds = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $originCourseIds[] = $course->id;
            }
        }

        $newCourseIds = explode(',', $courseIds);
        $addedCourseIds = array_diff($newCourseIds, $originCourseIds);

        if ($addedCourseIds) {
            foreach ($addedCourseIds as $courseId) {
                $courseTopic = new CourseTopicModel();
                $courseTopic->create([
                    'course_id' => $courseId,
                    'topic_id' => $topic->id,
                ]);
            }
        }

        $deletedCourseIds = array_diff($originCourseIds, $newCourseIds);

        if ($deletedCourseIds) {
            $courseTopicRepo = new CourseTopicRepo();
            foreach ($deletedCourseIds as $courseId) {
                $courseTopic = $courseTopicRepo->findCourseTopic($courseId, $topic->id);
                if ($courseTopic) {
                    $courseTopic->delete();
                }
            }
        }
    }

    protected function updateCourseCount(TopicModel $topic)
    {
        $topicRepo = new TopicRepo();

        $courseCount = $topicRepo->countCourses($topic->id);

        $topic->course_count = $courseCount;

        $topic->update();
    }

    protected function rebuildTopicCache(TopicModel $topic)
    {
        $cache = new TopicCache();

        $cache->rebuild($topic->id);
    }

}
