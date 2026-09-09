<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Review as ReviewModel;

class CourseRating extends Repository
{

    /**
     * @param int $courseId
     * @return float
     */
    public function averageRating(int $courseId): float
    {
        return $this->averageByRating($courseId, 'rating');
    }

    /**
     * @param int $courseId
     * @return float
     */
    public function averageRating1(int $courseId): float
    {
        return $this->averageByRating($courseId, 'rating1');
    }

    /**
     * @param int $courseId
     * @return float
     */
    public function averageRating2(int $courseId): float
    {
        return $this->averageByRating($courseId, 'rating2');
    }

    /**
     * @param int $courseId
     * @return float
     */
    public function averageRating3(int $courseId): float
    {
        return $this->averageByRating($courseId, 'rating3');
    }

    /**
     * @param int $courseId
     * @param string $rating
     * @return float
     */
    protected function averageByRating(int $courseId, string $rating): float
    {
        return (float)ReviewModel::average([
            'column' => $rating,
            'conditions' => 'course_id = :course_id: AND published = :published:',
            'bind' => ['course_id' => $courseId, 'published' => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

}
