<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;

class CourseTeacherList extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_teacher_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $users = $courseRepo->findTeachers($id);

        if ($users->count() == 0) {
            return [];
        }

        return $this->handleContent($users);
    }

    /**
     * @param UserModel[] $users
     * @return array
     */
    public function handleContent($users)
    {
        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'vip' => $user->vip,
                'title' => $user->title,
                'about' => $user->about,
            ];
        }

        return $result;
    }

}
