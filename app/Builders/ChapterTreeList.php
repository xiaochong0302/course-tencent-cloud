<?php

namespace App\Builders;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ChapterTreeList extends Builder
{

    /**
     * @param int $courseId
     * @return array
     */
    public function handle($courseId)
    {
        $list = [];

        $chapters = $this->findChapters($courseId);

        if ($chapters->count() == 0) {
            return [];
        }

        foreach ($chapters as $chapter) {
            $list[] = [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'priority' => $chapter->priority,
                'children' => $this->handleChildren($chapter),
            ];
        }

        return $list;
    }

    /**
     * @param ChapterModel $chapter
     * @return array
     */
    protected function handleChildren(ChapterModel $chapter)
    {
        $lessons = $this->findLessons($chapter->id);

        if ($lessons->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($lessons as $lesson) {

            /**
             * @var $attrs array
             */
            $attrs = $lesson->attrs;

            if ($chapter->model == CourseModel::MODEL_VOD) {
                unset($attrs['file_id'], $attrs['file_status']);
            }

            $list[] = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'free' => $lesson->free,
                'attrs' => $attrs,
            ];
        }

        return $list;
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    protected function findChapters($courseId)
    {
        return ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('parent_id = 0 AND deleted = 0')
            ->orderBy('priority ASC, id ASC')
            ->execute();
    }

    /**
     * @param int $chapterId
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    protected function findLessons($chapterId)
    {
        return ChapterModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $chapterId])
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC, id ASC')
            ->execute();
    }

}
