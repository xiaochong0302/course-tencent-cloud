<?php

namespace App\Library\Cache\Frontend;

class Json extends \Phalcon\Cache\Frontend\Json
{

    /**
     * Serializes data before storing them
     *
     * @param mixed $data
     * @return string
     */
    public function beforeStore($data)
    {
        $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

        return json_encode($data, $options);
    }

}
