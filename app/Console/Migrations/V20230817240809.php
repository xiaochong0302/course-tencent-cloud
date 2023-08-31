<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;

class V20230817240809 extends Migration
{

    public function run()
    {
        $this->handleCourseResourceCount();
    }

    protected function handleCourseResourceCount()
    {
        $courses = CourseModel::find();

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {
            if ($course->resource_count > 0) {
                $this->recountCourseResources($course);
            }
        }
    }

    protected function recountCourseResources(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $lessons = $courseRepo->findLessons($course->id);

        $chapterRepo = new ChapterRepo();

        $resourceCount = 0;

        if ($lessons->count() > 0) {
            foreach ($lessons as $lesson) {
                if ($lesson->deleted == 0) {
                    $resourceCount += $chapterRepo->countResources($lesson->id);
                }
            }
        }

        $course->resource_count = $resourceCount;

        $course->update();
    }

}