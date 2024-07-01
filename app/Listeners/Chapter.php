<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Chapter as ChapterModel;
use Phalcon\Events\Event as PhEvent;

class Chapter extends Listener
{

    public function afterCreate(PhEvent $event, $source, ChapterModel $chapter)
    {

    }

    public function afterUpdate(PhEvent $event, $source, ChapterModel $chapter)
    {

    }

    public function afterDelete(PhEvent $event, $source, ChapterModel $chapter)
    {

    }

    public function afterRestore(PhEvent $event, $source, ChapterModel $chapter)
    {

    }

    public function afterView(PhEvent $event, $source, ChapterModel $chapter)
    {

    }

    public function afterLike(PhEvent $event, $source, ChapterModel $chapter): void
    {

    }

    public function afterUndoLike(PhEvent $event, $source, ChapterModel $chapter): void
    {

    }

}