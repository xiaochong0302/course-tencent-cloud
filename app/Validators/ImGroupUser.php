<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\ImGroup as ImGroupModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\User as UserRepo;

class ImGroupUser extends Validator
{

    public function checkGroup($id)
    {
        $validator = new ImGroup();

        return $validator->checkGroup($id);
    }

    public function checkUser($id)
    {
        $validator = new ImUser();

        return $validator->checkUser($id);
    }

    public function checkRemark($remark)
    {
        $value = $this->filter->sanitize($remark, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('im_group_user.remark_too_long');
        }

        return $remark;
    }

    public function checkGroupUser($groupId, $userId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($groupId, $userId);

        if (!$record) {
            throw new BadRequestException('im_group_user.not_found');
        }

        return $record;
    }

    public function checkIfJoined($groupId, $userId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($groupId, $userId);

        if ($record) {
            throw new BadRequestException('im_group_user.has_joined');
        }
    }

    public function checkIfAllowJoin($groupId, $userId)
    {
        $message = 'im_group_user.join_not_allowed';

        $groupRepo = new ImGroupRepo();
        $group = $groupRepo->findById($groupId);

        $userRepo = new UserRepo();
        $user = $userRepo->findById($userId);

        $staff = $user->admin_role > 0 || $user->edu_role == UserModel::EDU_ROLE_TEACHER;

        if ($group->type == ImGroupModel::TYPE_STAFF && !$staff) {
            throw new BadRequestException($message);
        }

        if ($group->course_id > 0) {

            $courseRepo = new CourseRepo();
            $course = $courseRepo->findById($group->course_id);

            $courseUserRepo = new CourseUserRepo();
            $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);

            if ($course->market_price > 0) {
                if ($course->vip_price > 0 && !$courseUser) {
                    throw new BadRequestException($message);
                }
                if ($course->vip_price == 0 && $user->vip == 0) {
                    throw new BadRequestException($message);
                }
            }
        }
    }

}
