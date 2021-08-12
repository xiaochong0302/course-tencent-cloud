<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class SyncCourseStatTask extends Task
{

    public function mainAction()
    {
        $courses = $this->findCourses();

        echo sprintf('pending courses: %s', $courses->count()) . PHP_EOL;

        if ($courses->count() == 0) return;

        echo '------ start sync course stat task ------' . PHP_EOL;

        foreach ($courses as $course) {
            $this->recountUsers($course);
        }

        echo '------ end sync course stat task ------' . PHP_EOL;
    }

    protected function recountUsers(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $userCount = $courseRepo->countUsers($course->id);

        $course->user_count = $userCount;

        $course->update();
    }

    protected function findCourses()
    {
        return CourseModel::query()
            ->where('published = 1')
            ->execute();
    }

}
