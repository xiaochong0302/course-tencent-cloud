<?php

namespace App\Repos;

use App\Models\Resource as ResourceModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Resource extends Repository
{

    /**
     * @param int $id
     * @return ResourceModel|Model|bool
     */
    public function findById($id)
    {
        return ResourceModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ResourceModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ResourceModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ResourceModel[]
     */
    public function findByCourseId($courseId)
    {
        return ResourceModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->execute();
    }

    /**
     * @param int $chapterId
     * @return ResultsetInterface|Resultset|ResourceModel[]
     */
    public function findByChapterId($chapterId)
    {
        return ResourceModel::query()
            ->where('chapter_id = :chapter_id:', ['chapter_id' => $chapterId])
            ->execute();
    }

}
