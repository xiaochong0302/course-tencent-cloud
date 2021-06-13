<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Teacher\Console;

use App\Models\ChapterLive as ChapterLiveModel;
use App\Services\Live as LiveService;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;

class LivePushUrl extends LogicService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $service = new LiveService();

        $steamName = ChapterLiveModel::generateStreamName($chapter->id);

        return $service->getPushUrl($steamName);
    }

}
