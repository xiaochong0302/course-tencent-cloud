<?php

namespace App\Library\Mvc;

class View extends \Phalcon\Mvc\View
{

    public function setVars(array $params, $merge = true)
    {
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $params[$key] = kg_array_object($param);
            }
        }

        parent::setVars($params, $merge);
    }

    public function setVar($key, $value)
    {
        if (is_array($value)) {
            $value = kg_array_object($value);
        }

        parent::setVar($key, $value);
    }

}