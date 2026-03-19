<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\CourseRelated as CourseRelatedModel;
use Phalcon\Mvc\Model;

class CourseRelated extends Repository
{

    /**
     * @param int $courseId
     * @param int $relatedId
     * @return CourseRelatedModel|Model|bool
     */
    public function findCourseRelated($courseId, $relatedId)
    {
        return CourseRelatedModel::findFirst([
            'conditions' => 'course_id = :course_id: AND related_id = :related_id:',
            'bind' => ['course_id' => $courseId, 'related_id' => $relatedId],
        ]);
    }

}
