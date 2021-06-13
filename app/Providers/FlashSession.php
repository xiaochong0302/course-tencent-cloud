<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Flash\Session as PhFlashSession;

class FlashSession extends Provider
{

    protected $serviceName = 'flashSession';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            return new PhFlashSession([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]);
        });
    }

}
