<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Utils;

use App\Models\Course as CourseModel;
use App\Services\Service as AppService;

class CourseScore extends AppService
{

    public function handle(CourseModel $course)
    {
        if ($course->market_price == 0) {
            $score = $this->calculateFreeCourseScore($course);
        } else {
            $score = $this->calculateChargeCourseScore($course);
        }

        $course->score = $score;

        $course->update();
    }

    protected function calculateFreeCourseScore(CourseModel $course)
    {
        $weight = [
            'factor1' => 0.1,
            'factor2' => 0.25,
            'factor3' => 0.2,
            'factor4' => 0.1,
            'factor5' => 0.25,
            'factor6' => 0.1,
        ];

        return $this->calculateCourseScore($course, $weight);
    }

    protected function calculateChargeCourseScore(CourseModel $course)
    {
        $weight = [
            'factor1' => 0.1,
            'factor2' => 0.3,
            'factor3' => 0.15,
            'factor4' => 0.15,
            'factor5' => 0.2,
            'factor6' => 0.1,
        ];

        return $this->calculateCourseScore($course, $weight);
    }

    protected function calculateCourseScore(CourseModel $course, $weight)
    {
        $items = [
            'factor1' => 0.0,
            'factor2' => 0.0,
            'factor3' => 0.0,
            'factor4' => 0.0,
            'factor5' => 0.0,
            'factor6' => 0.0,
        ];

        if ($course->featured == 1) {
            $items['factor1'] = 7 * $weight['factor1'];
        }

        if ($course->user_count > 0) {
            $items['factor2'] = log($course->user_count) * $weight['factor2'];
        }

        if ($course->favorite_count > 0) {
            $items['factor3'] = log($course->favorite_count) * $weight['factor3'];
        }

        if ($course->consult_count > 0) {
            $items['factor4'] = log($course->consult_count) * $weight['factor4'];
        }

        if ($course->review_count > 0 && $course->rating > 0) {
            $items['factor5'] = log($course->review_count * $course->rating) * $weight['factor5'];
        }

        $sumCount = $course->lesson_count + $course->package_count + $course->resource_count;

        if ($sumCount > 0) {
            $items['factor6'] = log($sumCount) * $weight['factor6'];
        }

        $score = array_sum($items) / log(time() - $course->create_time);

        return round($score, 4);
    }

}
