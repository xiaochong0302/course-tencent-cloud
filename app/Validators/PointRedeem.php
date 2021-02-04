<?php

namespace App\Validators;

use App\Exceptions\BadRequest;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;
use App\Models\PointGift as PointGiftModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\User as UserRepo;

class PointRedeem extends Validator
{

    public function checkGift($giftId)
    {
        $validator = new PointGift();

        return $validator->checkGift($giftId);
    }

    public function checkIfAllowRedeem(PointGiftModel $gift, UserModel $user)
    {
        $this->checkPointBalance($gift, $user);

        if ($gift->type == PointGiftModel::TYPE_COURSE) {

            $validator = new Course();

            $course = $validator->checkCourse($gift->attrs['id']);

            $this->checkIfAllowRedeemCourse($course, $user);

        } elseif ($gift->type == PointGiftModel::TYPE_GOODS) {

            $this->checkIfAllowRedeemCommodity($user);
        }
    }

    protected function checkIfAllowRedeemCourse(CourseModel $course, UserModel $user)
    {
        if ($course->published == 0) {
            throw new BadRequestException('point_redeem.course_not_published');
        }

        if ($course->market_price == 0) {
            throw new BadRequestException('point_redeem.course_free');
        }

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);

        if ($courseUser->expiry_time > time()) {
            throw new BadRequestException('point_redeem.course_owned');
        }
    }

    protected function checkIfAllowRedeemCommodity(UserModel $user)
    {
        $userRepo = new UserRepo();

        $contact = $userRepo->findUserContact($user->id);

        if (!$contact) {
            throw new BadRequestException('point_redeem.no_user_contact');
        }
    }

    protected function checkPointBalance(PointGiftModel $gift, UserModel $user)
    {
        $userRepo = new UserRepo();

        $balance = $userRepo->findUserBalance($user->id);

        if (!$balance || $balance->point < $gift->point) {
            throw new BadRequestException('point_redeem.no_enough_point');
        }
    }

}
