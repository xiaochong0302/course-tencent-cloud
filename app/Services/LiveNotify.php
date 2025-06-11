<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Notice\External\LiveBegin as LiveBeginNotice;
use Phalcon\Logger\Adapter\File as FileLogger;

class LiveNotify extends Service
{

    /**
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger('live');
    }

    public function handle()
    {
        $time = $this->request->getPost('t', 'int');
        $sign = $this->request->getPost('sign', 'string');
        $action = $this->request->getQuery('action', 'string');

        $this->logger->debug('Received Live Notify Data: ' . kg_json_encode([
                't' => $time,
                'sign' => $sign,
                'action' => $action,
            ]));

        if (!$this->checkSign($sign, $time)) return false;

        $result = false;

        switch ($action) {
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
        $streamId = $this->request->getPost('stream_id', 'string');

        $chapter = $this->getChapter($streamId);

        $this->logger->debug("Chapter:{$chapter->id} Stream Begin");

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        $attrs['stream']['status'] = ChapterModel::SS_ACTIVE;

        $chapter->update(['attrs' => $attrs]);

        $chapterLive = $this->getChapterLive($chapter->id);

        $chapterLive->update(['status' => ChapterLiveModel::STATUS_ACTIVE]);

        $this->rebuildCatalogCache($chapter);

        $this->handleStreamBeginNotice($chapter);

        return true;
    }

    /**
     * 断流
     */
    protected function handleStreamEnd()
    {
        $streamId = $this->request->getPost('stream_id', 'string');

        $chapter = $this->getChapter($streamId);

        $this->logger->info("Chapter:{$chapter->id} Stream End");

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        $attrs['stream']['status'] = ChapterModel::SS_INACTIVE;

        $chapter->update(['attrs' => $attrs]);

        $chapterLive = $this->getChapterLive($chapter->id);

        $chapterLive->update(['status' => ChapterLiveModel::STATUS_INACTIVE]);

        $this->rebuildCatalogCache($chapter);

        return true;
    }

    /**
     * 录制
     */
    protected function handleRecord()
    {
        return true;
    }

    /**
     * 截图
     */
    protected function handleSnapshot()
    {
        return true;
    }

    /**
     * 鉴黄
     */
    protected function handlePorn()
    {
        return true;
    }

    protected function handleStreamBeginNotice(ChapterModel $chapter)
    {
        /**
         * 防止发送多次通知
         */
        $cache = $this->getCache();

        $keyName = "live_notify:{$chapter->id}";

        if ($cache->get($keyName)) return;

        $cache->save($keyName, time(), 86400);

        $courseUserRepo = new CourseUserRepo();

        $courseUsers = $courseUserRepo->findByCourseId($chapter->course_id);

        if ($courseUsers->count() == 0) return;

        $notice = new LiveBeginNotice();

        foreach ($courseUsers as $courseUser) {
            $notice->createTask($chapter, $courseUser);
        }
    }

    protected function rebuildCatalogCache(ChapterModel $chapter)
    {
        $cache = new CourseChapterListCache();

        $cache->rebuild($chapter->course_id);
    }

    protected function getChapter($streamName)
    {
        $id = ChapterLiveModel::parseFromStreamName($streamName);

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
        if (!$sign || !$time) return false;

        if ($time < time()) return false;

        $notify = $this->getSettings('live.notify');

        $mySign = md5($notify['auth_key'] . $time);

        return $sign == $mySign;
    }

}
