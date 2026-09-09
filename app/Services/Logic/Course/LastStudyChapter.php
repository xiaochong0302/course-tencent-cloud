<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class LastStudyChapter extends LogicService
{

    use CourseTrait;
    use ChapterTrait;

    /**
     * @var array
     */
    protected array $courseChapters = [];

    public function handle(int $id): ?int
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getLoginUser(true);

        $cache = new CourseChapterListCache();

        $this->courseChapters = $cache->get($course->id);

        $courseRepo = new CourseRepo();

        $chapterUser = $courseRepo->findLastChapterUser($course->id, $user->id);

        // 不存在学习记录，返回第一课时
        if (!$chapterUser) {
            return $this->getFirstLessonId();
        }

        // 尚未完成课时，继续学习
        if ($chapterUser->consumed == 0) {
            return $this->getFinalLessonId($chapterUser->chapter_id);
        }

        $lessonId = $this->getNextLessonId($chapterUser->chapter_id);

        if ($lessonId) {
            return $this->getFinalLessonId($lessonId);
        }

        return $this->getFirstLessonId();
    }

    protected function getFinalLessonId(int $lessonId): ?int
    {
        $chapter = $this->checkChapter($lessonId);

        if ($chapter->published == 1 && $chapter->deleted == 0) {
            return $chapter->id;
        }

        return $this->getFirstLessonId();
    }

    protected function getNextLessonId(int $lessonId): ?int
    {
        $chapterIndex = $this->getChapterIndex($lessonId);
        $lessonIndex = $this->getLessonIndex($lessonId);

        foreach ($this->courseChapters as $i => $chapter) {
            foreach ($chapter['children'] as $j => $lesson) {
                if ($lesson['published'] == 1) {
                    if ($i == $chapterIndex && $j > $lessonIndex) {
                        return $lesson['id'];
                    } elseif ($i > $chapterIndex) {
                        return $lesson['id'];
                    }
                }
            }
        }

        return null;
    }

    protected function getFirstLessonId(): ?int
    {
        foreach ($this->courseChapters as $chapter) {
            foreach ($chapter['children'] as $lesson) {
                if ($lesson['published'] == 1) {
                    return $lesson['id'];
                }
            }
        }

        return null;
    }

    protected function getChapterIndex(int $lessonId): ?int
    {
        foreach ($this->courseChapters as $index => $chapter) {
            foreach ($chapter['children'] as $lesson) {
                if ($lesson['id'] == $lessonId) {
                    return $index;
                }
            }
        }

        return null;
    }

    protected function getLessonIndex(int $lessonId): ?int
    {
        foreach ($this->courseChapters as $chapter) {
            foreach ($chapter['children'] as $index => $lesson) {
                if ($lesson['id'] == $lessonId) {
                    return $index;
                }
            }
        }

        return null;
    }

}
