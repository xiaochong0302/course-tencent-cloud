<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Review as ReviewModel;
use Phalcon\Events\Event as PhEvent;

class Review extends Listener
{

    public function afterCreate(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterUpdate(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterDelete(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterRestore(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterApprove(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterReject(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterLike(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

    public function afterUndoLike(PhEvent $event, object $source, ReviewModel $review): void
    {

    }

}
