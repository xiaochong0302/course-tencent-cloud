<?php

namespace App\Listeners;

use App\Models\ImMessage as ImMessageModel;
use App\Services\Logic\Point\PointHistory as PointHistoryService;
use Phalcon\Events\Event as PhEvent;

class ImMessage extends Listener
{

    public function afterCreate(PhEvent $event, $source, ImMessageModel $message)
    {
        $this->handleDiscussPoint($message);
    }

    protected function handleDiscussPoint(ImMessageModel $message)
    {
        $todayDate = date('Ymd');

        $keyName = sprintf('im_discuss:%s:%s', $message->sender_id, $todayDate);

        $cache = $this->getCache();

        $content = $cache->get($keyName);

        if ($content) return;

        $service = new PointHistoryService();

        $service->handleImDiscuss($message);

        $tomorrow = strtotime($todayDate) + 86400;

        $lifetime = $tomorrow - time();

        $cache->save($keyName, 1, $lifetime);
    }

}