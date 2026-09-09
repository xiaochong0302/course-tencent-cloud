<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\CourseUser as CourseUserModel;
use App\Models\KgOwnership as KgOwnershipModel;
use App\Models\Review as ReviewModel;
use App\Services\CourseStat as CourseStatService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class DefaultReviewTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $courseUsers = $this->findCourseUsers();

        echo sprintf('pending reviews: %s', $courseUsers->count()) . PHP_EOL;

        if ($courseUsers->count() == 0) return;

        echo '------ start default review task ------' . PHP_EOL;

        foreach ($courseUsers as $courseUser) {

            $courseUser->reviewed = 1;
            $courseUser->update();

            $review = new ReviewModel();
            $review->course_id = $courseUser->course_id;
            $review->user_id = $courseUser->user_id;
            $review->rating = 5;
            $review->rating1 = 5;
            $review->rating2 = 5;
            $review->rating3 = 5;
            $review->create();

            $this->updateCourseReviews($courseUser->course->id);
            $this->updateCourseRating($courseUser->course_id);
        }

        echo '------ end default review task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function updateCourseReviews(int $courseId): void
    {
        $service = new CourseStatService();

        $service->updateReviewCount($courseId);
    }

    protected function updateCourseRating(int $courseId): void
    {
        $service = new CourseStatService();

        $service->updateRating($courseId);
    }

    /**
     * 查找待评价的记录
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseUserModel[]
     */
    protected function findCourseUsers(int $limit = 1000)
    {
        $time = strtotime('-3 month');

        $excludeTypes = [
            KgOwnershipModel::SOURCE_TRIAL,
        ];

        return CourseUserModel::query()
            ->where('reviewed = :reviewed:', ['reviewed' => 0])
            ->andWhere('create_time < :create_time:', ['create_time' => $time])
            ->notInWhere('source_type', $excludeTypes)
            ->limit($limit)
            ->execute();
    }

}
