<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Validators\Connect as ConnectValidator;

class ConnectDelete extends LogicService
{

    public function handle($id)
    {
        $user = $this->getLoginUser();

        $validator = new ConnectValidator();

        $connect = $validator->checkConnect($id);

        $validator->checkOwner($user->id, $connect->user_id);

        $connect->deleted = 1;

        $connect->update();
    }

}
