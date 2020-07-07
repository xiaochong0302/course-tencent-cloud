<?php

namespace App\Library\Http;

use Phalcon\Http\ResponseInterface;

class Response extends \Phalcon\Http\Response
{

    public function setJsonContent($content, $jsonOptions = 0, $depth = 512): ResponseInterface
    {
        $jsonOptions = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

        return parent::setJsonContent($content, $jsonOptions, $depth);
    }

}