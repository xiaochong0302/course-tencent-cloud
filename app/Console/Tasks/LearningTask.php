<?php

namespace App\Console\Tasks;

use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use Phalcon\Cli\Task;

class LearningTask extends Task
{

    /**
     * @var \App\Library\Cache\Backend\Redis
     */
    protected $cache;

    public function mainAction()
    {
        $this->cache = $this->getDI()->get('cache');

        $keys = $this->cache->queryKeys('learning:');

        if (empty($keys)) {
            return;
        }

        $keys = array_slice($keys, 0, 500);

        foreach ($keys as $key) {
            $lastKey = $this->cache->getRawKeyName($key);
            $this->handleLearning($lastKey);
        }
    }

    protected function handleLearning($key)
    {
        $content = $this->cache->get($key);

        if (empty($content->user_id)) {
            return;
        }

        if (!empty($content->client_ip)) {
            $region = kg_ip2region($content->client_ip);
            $content->country = $region->country;
            $content->province = $region->province;
            $content->city = $region->city;
        }

        $learningRepo = new LearningRepo();

        $learning = $learningRepo->findByRequestId($content->request_id);

        if (!$learning) {
            $learning = new LearningModel();
            $data = kg_object_array($content);
            $learning->create($data);
        } else {
            $learning->duration += $content->duration;
            $learning->update();
        }

        $this->updateChapterUser($content->chapter_id, $content->user_id, $content->duration, $content->position);
        $this->updateCourseUser($content->course_id, $content->user_id, $content->duration);

        $this->cache->delete($key);
    }

    protected function updateChapterUser($chapterId, $userId, $duration = 0, $position = 0)
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findChapterUser($chapterId, $userId);

        if (!$chapterUser) {
            return;
        }

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        if (!$chapter) {
            return;
        }

        $chapter->duration = $chapter->attrs['duration'] ?: 0;

        $chapterUser->duration += $duration;
        $chapterUser->position = floor($position);

        /**
         * 观看时长超过视频时长80%标记完成学习
         */
        if ($chapterUser->duration > $chapter->duration * 0.8) {
            if ($chapterUser->finished == 0) {
                $chapterUser->finished = 1;
                $this->updateCourseProgress($chapterUser->course_id, $chapterUser->user_id);
            }
        }

        $chapterUser->update();
    }

    protected function updateCourseUser($courseId, $userId, $duration)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if ($courseUser) {
            $courseUser->duration += $duration;
            $courseUser->update();
        }
    }

    protected function updateCourseProgress($courseId, $userId)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if ($courseUser) {
            $count = $courseUserRepo->countFinishedChapters($courseId, $userId);
            $courseUser->progress = intval(100 * $count / $course->lesson_count);
            $courseUser->update();
        }
    }

}
