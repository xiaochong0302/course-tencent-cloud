<?php

namespace App\Services;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Chapter as ChapterRepo;

class LiveNotify extends Service
{

    public function handle()
    {
        $time = $this->request->getPost('t');
        $sign = $this->request->getPost('sign');
        $type = $this->request->getQuery('action');

        if (!$this->checkSign($sign, $time)) {
            return false;
        }

        $result = false;

        switch ($type) {
            case 'streamBegin':
                $result = $this->handleStreamBegin();
                break;
            case 'streamEnd':
                $result = $this->handleStreamEnd();
                break;
            case 'record':
                $result = $this->handleRecord();
                break;
            case 'snapshot':
                $result = $this->handleSnapshot();
                break;
            case 'porn':
                $result = $this->handlePorn();
                break;
        }

        return $result;
    }

    /**
     * 推流
     */
    protected function handleStreamBegin()
    {
        $steamId = $this->request->getPost('stream_id');

        $chapter = $this->getChapter($steamId);

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        $attrs['stream']['status'] = ChapterModel::SS_ACTIVE;

        $chapter->update(['attrs' => $attrs]);

        $chapterLive = $this->getChapterLive($chapter->id);

        $chapterLive->update(['status' => ChapterLiveModel::STATUS_ACTIVE]);

        /**
         * @todo 发送直播通知
         */

        return true;
    }

    /**
     * 断流
     */
    protected function handleStreamEnd()
    {
        $steamId = $this->request->getPost('stream_id');

        $chapter = $this->getChapter($steamId);

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        $attrs['stream']['status'] = ChapterModel::SS_INACTIVE;

        $chapter->update(['attrs' => $attrs]);

        $chapterLive = $this->getChapterLive($chapter->id);

        $chapterLive->update(['status' => ChapterLiveModel::STATUS_INACTIVE]);

        return true;
    }

    /**
     * 录制
     */
    protected function handleRecord()
    {

    }

    /**
     * 截图
     */
    protected function handleSnapshot()
    {

    }

    /**
     * 鉴黄
     */
    protected function handlePorn()
    {

    }

    protected function getChapter($streamId)
    {
        $id = (int)str_replace('chapter_', '', $streamId);

        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findById($id);
    }

    protected function getChapterLive($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterLive($chapterId);
    }

    /**
     * 检查签名
     *
     * @param string $sign
     * @param int $time
     * @return bool
     */
    protected function checkSign($sign, $time)
    {
        if (empty($sign) || empty($time)) {
            return false;
        }

        if ($time < time()) {
            return false;
        }

        $live = $this->getSectionSettings('live');

        $mySign = md5($live['notify_auth_key'] . $time);

        return $sign == $mySign;
    }

}