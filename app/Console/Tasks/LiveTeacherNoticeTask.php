<?php

namespace App\Console\Tasks;

use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\DingTalk\Notice\LiveTeacher as LiveTeacherNotice;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class LiveTeacherNoticeTask extends Task
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

        $chapterIds = $redis->sMembers($keyName);

        if (count($chapterIds) == 0) return;

        $chapterRepo = new ChapterRepo();

        $notice = new LiveTeacherNotice();

        foreach ($chapterIds as $chapterId) {

            $live = $chapterRepo->findChapterLive($chapterId);

            if ($live->start_time - time() < 30 * 60) {

                $notice->handle($live);

                $redis->sRem($keyName, $chapterId);
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
        return 'live_teacher_notice';
    }

}