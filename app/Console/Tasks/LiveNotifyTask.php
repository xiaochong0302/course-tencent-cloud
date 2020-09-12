<?php

namespace App\Console\Tasks;

use App\Models\CourseUser as CourseUserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\LiveNotify as LiveNotifyService;
use App\Services\Smser\Live as LiveSmser;

class LiveNotifyTask extends Task
{

    public function mainAction()
    {
        $cache = $this->getCache();

        $redis = $this->getRedis();

        $service = new LiveNotifyService();

        $key = $service->getNotifyKey();

        $chapterIds = $redis->sMembers($key);

        if (!$chapterIds) return;

        $sentKey = $service->getSentNotifyKey();

        $sentChapterIds = $redis->sMembers($sentKey);

        foreach ($chapterIds as $chapterId) {
            if (!in_array($chapterId, $sentChapterIds)) {
                $this->sendNotification($chapterId);
            } else {
                $redis->sAdd($sentKey, $chapterId);
            }
        }

        if ($redis->sCard($sentKey) == 1) {
            $redis->expire($sentKey, 86400);
        }
    }

    protected function sendNotification($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $chapterLive = $chapterRepo->findChapterLive($chapterId);

        if (!$chapterLive) return;

        $targetUserIds = $this->findTargetUserIds($chapterLive->course_id);

        if (!$targetUserIds) return;

        $smser = new LiveSmser();

        foreach ($targetUserIds as $userId) {
            $smser->handle($chapterId, $userId, $chapterLive->start_time);
        }
    }

    protected function findTargetUserIds($courseId)
    {
        $sourceTypes = [
            CourseUserModel::SOURCE_CHARGE,
            CourseUserModel::SOURCE_VIP,
        ];

        $rows = CourseUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('role_type = :role_type:', ['role_type' => CourseUserModel::ROLE_STUDENT])
            ->inWhere('source_type', $sourceTypes)
            ->execute();

        if ($rows->count() == 0) {
            return [];
        }

        return kg_array_column($rows->toArray(), 'user_id');
    }

}
