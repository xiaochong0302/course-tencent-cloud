<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services\Traits;

use App\Library\Validators\Common as CommonValidator;
use App\Repos\Account as AccountRepo;

trait AccountSearchTrait
{

    protected function handleAccountSearchParams($params)
    {
        $key = null;

        if (isset($params['user_id'])) {
            $key = 'user_id';
        } elseif (isset($params['owner_id'])) {
            $key = 'owner_id';
        }

        if ($key == null) return $params;

        $accountRepo = new AccountRepo();

        /**
         * 兼容用户编号｜手机号码｜邮箱地址查询
         */
        if (!empty($params[$key])) {
            if (CommonValidator::phone($params[$key])) {
                $account = $accountRepo->findByPhone($params[$key]);
                $params[$key] = $account ? $account->id : -1000;
            } elseif (CommonValidator::email($params[$key])) {
                $account = $accountRepo->findByEmail($params[$key]);
                $params[$key] = $account ? $account->id : -1000;
            }
        }

        return $params;
    }

}
