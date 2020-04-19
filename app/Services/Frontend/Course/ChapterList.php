<?php

namespace App\Services\Frontend\Course;

use App\Builders\ChapterTreeList as ChapterListBuilder;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;
use Phalcon\Mvc\Model\Resultset;

class ChapterList extends Service
{

    use CourseTrait;

    /**
     * @var CourseModel
     */
    protected $course;

    /**
     * @var UserModel
     */
    protected $user;

    public function getChapters($id)
    {
        $this->course = $this->checkCourse($id);

        $this->user = $this->getCurrentUser();

        $this->setCourseUser($this->course, $this->user);

        $courseRepo = new CourseRepo();

        $chapters = $courseRepo->findChapters($id);

        return $this->handleChapters($chapters);
    }

    /**
     * @param Resultset $chapters
     * @return array
     */
    protected function handleChapters($chapters)
    {
        if ($chapters->count() == 0) {
            return [];
        }

        $builder = new ChapterListBuilder();

        $items = $chapters->toArray();

        $treeList = $builder->handleTreeList($items);

        $learningMapping = $this->getLearningMapping($this->course, $this->user);

        foreach ($treeList as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free']) ? 1 : 0;
                $progress = $learningMapping[$lesson['id']]['progress'] ?? 0;
                $lesson['me'] = [
                    'owned' => $owned,
                    'progress' => $progress,
                ];
            }
        }

        return $treeList;
    }

    protected function getLearningMapping(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) {
            return [];
        }

        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($course->id, $user->id);

        if ($userLearnings->count() == 0) {
            return [];
        }

        $mapping = [];

        foreach ($userLearnings as $learning) {
            $mapping[$learning['chapter_id']] = [
                'progress' => $learning['progress'],
            ];
        }

        return $mapping;
    }

}
