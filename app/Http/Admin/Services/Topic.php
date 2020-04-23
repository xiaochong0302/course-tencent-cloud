<?php

namespace App\Http\Admin\Services;

use App\Caches\Topic as TopicCache;
use App\Caches\TopicCourseList as TopicCourseListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseTopic as CourseTopicModel;
use App\Models\Topic as TopicModel;
use App\Repos\CourseTopic as CourseTopicRepo;
use App\Repos\Topic as TopicRepo;
use App\Validators\Topic as TopicValidator;

class Topic extends Service
{

    public function getTopics()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pageRepo = new TopicRepo();

        return $pageRepo->paginate($params, $sort, $page, $limit);
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
        $data['summary'] = $validator->checkSummary($post['summary']);

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

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
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

    public function getXmCourses($id)
    {
        $topicRepo = new TopicRepo();

        $courses = $topicRepo->findCourses($id);

        $list = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $list[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'selected' => true,
                ];
            }
        }

        return $list;
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

        $cache = new TopicCourseListCache();

        $cache->rebuild($topic->id);
    }

    protected function findOrFail($id)
    {
        $validator = new TopicValidator();

        return $validator->checkTopic($id);
    }

}
