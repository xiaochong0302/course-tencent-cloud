<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use Phalcon\Di\Injectable;

class Builder extends Injectable
{

    public function objects(array $items)
    {
        return kg_array_object($items);
    }

}
