<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

    public function getClientAddress($trustForwardedHeader = true)
    {
        $address = $_SERVER['REMOTE_ADDR'] ?: '0.0.0.0';

        if ($trustForwardedHeader) {
            if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
                $address = $_SERVER['HTTP_X_REAL_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'];
                list($address) = explode(',', $forwardedFor);
            }
        }

        return trim($address);
    }

}
