<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImNotice as ImNoticeRepo;
use App\Repos\ImUser as ImUserRepo;

trait ImNoticeTrait
{

    public function countUnreadNotices()
    {
        $user = $this->getLoginUser();

        $userRepo = new ImUserRepo();

        return $userRepo->countUnreadNotices($user->id);
    }

    public function getNotices()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['receiver_id'] = $user->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $noticeRepo = new ImNoticeRepo();

        return $noticeRepo->paginate($params, $sort, $page, $limit);
    }

    public function readNotices()
    {
        $user = $this->getLoginUser();

        $userRepo = new ImUserRepo();

        $messages = $userRepo->findUnreadNotices($user->id);

        if ($messages->count() > 0) {
            foreach ($messages as $message) {
                $message->viewed = 1;
                $message->update();
            }
        }
    }

}