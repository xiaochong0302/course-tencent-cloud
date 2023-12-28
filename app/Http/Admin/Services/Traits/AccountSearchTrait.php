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
        $accountRepo = new AccountRepo();

        /**
         * 兼容用户编号｜手机号码｜邮箱地址查询
         */
        if (!empty($params['user_id'])) {
            if (CommonValidator::phone($params['user_id'])) {
                $account = $accountRepo->findByPhone($params['user_id']);
                $params['user_id'] = $account ? $account->id : -1000;
            } elseif (CommonValidator::email($params['user_id'])) {
                $account = $accountRepo->findByEmail($params['user_id']);
                $params['user_id'] = $account ? $account->id : -1000;
            }
        }

        /**
         * 兼容用户编号｜手机号码｜邮箱地址查询
         */
        if (!empty($params['owner_id'])) {
            if (CommonValidator::phone($params['owner_id'])) {
                $account = $accountRepo->findByPhone($params['owner_id']);
                $params['owner_id'] = $account ? $account->id : -1000;
            } elseif (CommonValidator::email($params['owner_id'])) {
                $account = $accountRepo->findByEmail($params['owner_id']);
                $params['owner_id'] = $account ? $account->id : -1000;
            }
        }

        return $params;
    }

}
