<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

class AppInfo
{

    protected $name = '酷瓜云课堂';

    protected $alias = 'CTC';

    protected $link = 'https://koogua.com';

    protected $version = '1.4.8';

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
    }

}