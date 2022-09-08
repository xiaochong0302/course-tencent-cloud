<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Services\Smser;

class Test extends Smser
{

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $identity = new Verify();

        return $identity->handle($phone);
    }

}
