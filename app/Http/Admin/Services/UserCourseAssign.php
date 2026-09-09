<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\KgOwnership as KgOwnershipModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Validators\CourseUser as CourseUserValidator;

class UserCourseAssign extends Service
{

    use CourseUserTrait;

    public function getXmCourses(): array
    {
        $courseRepo = new CourseRepo();

        $where = [
            'published' => 1,
            'deleted' => 0,
            'free' => 0,
        ];

        $items = $courseRepo->findAll($where);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $item->market_price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function assignCourse(int $userId): void
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $user = $validator->checkUser($userId);

        $expiryTime = $validator->checkExpiryTime($post['expiry_time']);

        $courseIds = $post['xm_course_ids'] ? explode(',', $post['xm_course_ids']) : [];

        if (empty($courseIds)) return;

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        $sourceType = KgOwnershipModel::SOURCE_MANUAL;

        foreach ($courses as $course) {
            $this->assignUserCourse($course, $user, $expiryTime, $sourceType);
        }
    }

}
