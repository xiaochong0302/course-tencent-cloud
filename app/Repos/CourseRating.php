<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\CourseRating as CourseRatingModel;
use App\Models\Review as ReviewModel;
use Phalcon\Mvc\Model;

class CourseRating extends Repository
{

    /**
     * @param int $courseId
     * @return CourseRatingModel|Model|bool
     */
    public function findByCourseId($courseId)
    {
        return CourseRatingModel::findFirst([
            'conditions' => 'course_id = :course_id:',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function averageRating($courseId)
    {
        return (float)ReviewModel::average([
            'column' => 'rating',
            'conditions' => 'course_id = :course_id: AND published = 1',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function averageRating1($courseId)
    {
        return (float)ReviewModel::average([
            'column' => 'rating1',
            'conditions' => 'course_id = :course_id: AND published = 1',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function averageRating2($courseId)
    {
        return (float)ReviewModel::average([
            'column' => 'rating2',
            'conditions' => 'course_id = :course_id: AND published = 1',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function averageRating3($courseId)
    {
        return (float)ReviewModel::average([
            'column' => 'rating3',
            'conditions' => 'course_id = :course_id: AND published = 1',
            'bind' => ['course_id' => $courseId],
        ]);
    }

}
