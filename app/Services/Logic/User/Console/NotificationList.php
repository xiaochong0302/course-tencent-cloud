<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Builders\NotificationList as NotificationListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Notification as NotificationRepo;
use App\Services\Logic\Service as LogicService;

class NotificationList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['receiver_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $notifyRepo = new NotificationRepo();

        $pager = $notifyRepo->paginate($params, $sort, $page, $limit);

        return $this->handleNotifications($pager);
    }

    protected function handleNotifications($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $notifications = $pager->items->toArray();

        $builder = new NotificationListBuilder();

        $users = $builder->getUsers($notifications);

        $items = [];

        foreach ($notifications as $key => $value) {

            $value['event_info'] = json_decode($value['event_info'], true);

            $sender = $users[$value['sender_id']] ?? new \stdClass();
            $receiver = $users[$value['receiver_id']] ?? new \stdClass();

            $items[] = [
                'id' => $value['id'],
                'viewed' => $value['viewed'],
                'event_id' => $value['event_id'],
                'event_type' => $value['event_type'],
                'event_info' => $value['event_info'],
                'create_time' => $value['create_time'],
                'sender' => $sender,
                'receiver' => $receiver,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
