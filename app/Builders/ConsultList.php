<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class ConsultList extends Builder
{

    public function handleCourses(array $consults)
    {
        $courses = $this->getCourses($consults);

        foreach ($consults as $key => $consult) {
            $consults[$key]['course'] = $courses[$consult['course_id']] ?? new \stdClass();
        }

        return $consults;
    }

    public function handleUsers(array $consults)
    {
        $users = $this->getUsers($consults);

        foreach ($consults as $key => $consult) {
            $consults[$key]['owner'] = $users[$consult['owner_id']] ?? new \stdClass();
            $consults[$key]['replier'] = $users[$consult['replier_id']] ?? new \stdClass();
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

        $userRepo = new UserRepo();

        $users = $userRepo->findShallowUserByIds($ids);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
