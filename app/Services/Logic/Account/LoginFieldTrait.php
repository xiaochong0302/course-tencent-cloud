<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Account;

trait LoginFieldTrait
{

    protected function handleLoginFields(array $params): array
    {
        /**
         * 使用[account|phone|email]做账户名字段兼容
         */
        if (isset($params['phone'])) {
            $params['account'] = $params['phone'];
        } elseif (isset($params['email'])) {
            $params['account'] = $params['email'];
        }

        return $params;
    }

}
