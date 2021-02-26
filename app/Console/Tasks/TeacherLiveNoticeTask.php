<?php

namespace App\Console\Tasks;

use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\ChapterLive as ChapterLiveRepo;
use App\Services\DingTalk\Notice\TeacherLive as TeacherLiveNotice;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class TeacherLiveNoticeTask extends Task
{

    /**
     * 生成讲师提醒
     */
    public function provideAction()
    {
        $lives = $this->findLives();

        if ($lives->count() == 0) return;

        $redis = $this->getRedis();

        $keyName = $this->getCacheKeyName();

        foreach ($lives as $live) {
            $redis->sAdd($keyName, $live->chapter_id);
        }

        $redis->expire($keyName, 86400);
    }

    /**
     * 消费讲师提醒
     */
    public function consumeAction()
    {
        $redis = $this->getRedis();

        $keyName = $this->getCacheKeyName();

        $liveIds = $redis->sMembers($keyName);

        if (count($liveIds) == 0) return;

        $liveRepo = new ChapterLiveRepo();

        $notice = new TeacherLiveNotice();

        foreach ($liveIds as $liveId) {

            $live = $liveRepo->findById($liveId);

            if ($live->start_time - time() < 30 * 60) {

                $notice->createTask($live);

                $redis->sRem($keyName, $liveId);
            }
        }
    }

    /**
     * @return ResultsetInterface|Resultset|ChapterLiveModel[]
     */
    protected function findLives()
    {
        $today = strtotime(date('Ymd'));

        return ChapterLiveModel::query()
            ->betweenWhere('start_time', $today, $today + 86400)
            ->execute();
    }

    protected function getCacheKeyName()
    {
        return 'teacher_live_notice_task';
    }

}