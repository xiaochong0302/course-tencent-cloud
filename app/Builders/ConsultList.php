<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Course as CourseRepo;

class ConsultList extends Builder
{

    public function handleCourses(array $consults)
    {
        $courses = $this->getCourses($consults);

        foreach ($consults as $key => $consult) {
            $consults[$key]['course'] = $courses[$consult['course_id']] ?? null;
        }

        return $consults;
    }

    public function handleUsers(array $consults)
    {
        $users = $this->getUsers($consults);

        foreach ($consults as $key => $consult) {
            $consults[$key]['owner'] = $users[$consult['owner_id']] ?? null;
            $consults[$key]['replier'] = $users[$consult['replier_id']] ?? null;
        }

        return $consults;
    }

    public function getCourses(array $consults)
    {
        $ids = kg_array_column($consults, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers(array $consults)
    {
        $ownerIds = kg_array_column($consults, 'owner_id');
        $replierIds = kg_array_column($consults, 'replier_id');
        $ids = array_merge($ownerIds, $replierIds);

        return $this->getShallowUserByIds($ids);
    }

}
