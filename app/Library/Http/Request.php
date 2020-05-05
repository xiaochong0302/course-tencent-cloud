<?php

namespace App\Library\Http;

use App\Exceptions\BadRequest;

class Request extends \Phalcon\Http\Request
{

    public function getPost($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        $contentType = $this->getContentType();

        if (stripos($contentType, 'form')) {
            return parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        } elseif (stripos($contentType, 'json')) {
            return $this->getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        } else {
            throw new BadRequest('sys.invalid_content_type');
        }
    }

}