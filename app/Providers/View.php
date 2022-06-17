<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use App\Library\Mvc\View as MyView;

class View extends Provider
{

    protected $serviceName = 'view';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            $view = new MyView();
            $view->registerEngines([
                '.volt' =>$this->di->getShared('volt'),
            ]);
            return $view;
        });
    }

}
