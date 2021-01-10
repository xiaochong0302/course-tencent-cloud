<?php

namespace App\Library\Http;

class Request extends \Phalcon\Http\Request
{

    /**
     * @return bool
     */
    public function isAjax(): bool
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
    public function isApi(): bool
    {
        $url = $this->get('_url');

        if ($this->hasHeader('X-Platform')) {
            return true;
        }

        if (stripos($url, '/api') !== false) {
            return true;
        }

        return false;
    }

    public function getPost($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
    {
        $contentType = $this->getContentType();

        if (stripos($contentType, 'json')) {
            $data = $this->getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        } else {
            $data = parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        }

        return $data;
    }

}