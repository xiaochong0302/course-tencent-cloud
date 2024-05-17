<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Connect as ConnectModel;
use App\Repos\Connect as ConnectRepo;

class UserConsole extends Service
{

    public function getWeChatOAConnect()
    {
        $user = $this->getLoginUser();

        $connectRepo = new ConnectRepo();

        return $connectRepo->findByUserId($user->id, ConnectModel::PROVIDER_WECHAT_OA);
    }

}
