<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Mvc;

use Phalcon\Mvc\View as PhView;

class View extends PhView
{

    public function setVars(array $params, $merge = true): PhView
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->handleVar($param);
        }

        return parent::setVars($params, $merge);
    }

    public function setVar($key, $value): PhView
    {
        $value = $this->handleVar($value);

        return parent::setVar($key, $value);
    }

    protected function handleVar($var)
    {
        /**
         * 分页数据
         */
        if (isset($var->total_items)) {
            $var->items = kg_array_object($var->items);
        } elseif (is_array($var)) {
            $var = kg_array_object($var);
        }

        return $var;
    }

}
