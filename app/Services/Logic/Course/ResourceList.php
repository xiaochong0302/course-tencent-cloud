<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Builders\ResourceList as ResourceListBuilder;
use App\Repos\Course as CourseRepo;
use App\Repos\Resource as ResourceRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ResourceList extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser(true);

        $this->setCourseUser($course, $user);

        $courseRepo = new CourseRepo();

        $lessons = $courseRepo->findLessons($course->id);

        if ($lessons->count() == 0) {
            return [];
        }

        $lessonIds = [];

        /**
         * 过滤掉未发布和已删除的课时
         */
        foreach ($lessons as $lesson) {
            if ($lesson->published == 1 && $lesson->deleted == 0) {
                $lessonIds[] = $lesson->id;
            }
        }

        $resourceRepo = new ResourceRepo();

        $resources = $resourceRepo->findByCourseId($course->id);

        if ($resources->count() == 0) {
            return [];
        }

        $builder = new ResourceListBuilder();

        $relations = $resources->toArray();

        foreach ($relations as $key => $relation) {
            if (!in_array($relation['chapter_id'], $lessonIds)) {
                unset($relations[$key]);
            }
        }

        $uploads = $builder->getUploads($relations);

        foreach ($uploads as $key => $upload) {
            $uploads[$key]['me'] = ['owned' => $this->ownedCourse ? 1 : 0];
        }

        return array_values($uploads);
    }

}
