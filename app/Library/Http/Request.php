<?php

namespace App\Library\Http;

use App\Exceptions\BadRequest;

class Request extends \Phalcon\Http\Request
{

    /**
     * @return bool
     */
    public function isAjax()
    {
        if (parent::isAjax()) {
            return true;
        }

        $contentType = $this->getContentType();

        if (stripos($contentType, 'json') !== false) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isApi()
    {
        $url = $this->get('_url');

        if (stripos($url, '/api') !== false) {
            return true;
        }

        return false;
    }

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