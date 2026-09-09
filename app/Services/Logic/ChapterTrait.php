<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterDoc as ChapterDocModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterVod as ChapterVodModel;
use App\Validators\Chapter as ChapterValidator;

trait ChapterTrait
{

    protected function checkChapterVod(int $id): ChapterVodModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterVod($id);
    }

    protected function checkChapterLive(int $id): ChapterLiveModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterLive($id);
    }

    protected function checkChapterRead(int $id): ChapterReadModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterRead($id);
    }

    protected function checkChapterDoc(int $id): ChapterDocModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterDoc($id);
    }

    protected function checkChapter(int $id): ChapterModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

    protected function checkChapterCache(int $id): ChapterModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterCache($id);
    }

    protected function checkChapterLiveCache(int $id): ChapterLiveModel
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterLiveCache($id);
    }

}
