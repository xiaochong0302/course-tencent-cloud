<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

/**
 * @property string $name
 * @property string $alias
 * @property string $link
 * @property string $version
 */
class AppInfo
{

    protected string $name = '酷瓜云课堂';

    protected string $alias = 'CTC';

    protected string $link = 'https://www.koogua.com';

    protected string $version = '2.0.1';

    public function __get(string $name): ?string
    {
        return $this->get($name);
    }

    public function get(string $name): ?string
    {
        return $this->{$name} ?? null;
    }

}
