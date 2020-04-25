<?php

namespace App\Library\Http;

class Response extends \Phalcon\Http\Response
{

    public function setJsonContent($content, $jsonOptions = 0, $depth = 512)
    {
        $jsonOptions = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

        parent::setJsonContent($content, $jsonOptions, $depth);
    }

}