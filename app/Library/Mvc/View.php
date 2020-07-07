<?php

namespace App\Library\Mvc;

use Phalcon\Mvc\View as PhView;

class View extends PhView
{

    public function setVars(array $params, bool $merge = true): PhView
    {
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $params[$key] = kg_array_object($param);
            }
        }

        return parent::setVars($params, $merge);
    }

    public function setVar(string $key, $value): PhView
    {
        if (is_array($value)) {
            $value = kg_array_object($value);
        }

        return parent::setVar($key, $value);
    }

}