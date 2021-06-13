<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Model extends \Phalcon\Mvc\Model
{

    public function initialize()
    {
        $this->setup([
            'notNullValidations' => false,
        ]);

        $this->useDynamicUpdate(true);
    }

}
