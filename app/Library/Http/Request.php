<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Http;

class Request extends \Phalcon\Http\Request
{

    public function isAjax(): bool
    {
        if (parent::isAjax()) {
            return true;
        }

        $contentType = $this->getContentType();

        if (!empty($contentType) && str_contains($contentType, 'json')) {
            return true;
        }

        return false;
    }

    public function isApi(): bool
    {
        if ($this->hasHeader('X-Platform')) {
            return true;
        }

        $url = $this->get('_url');

        if (!empty($url) && str_contains($url, '/api')) {
            return true;
        }

        return false;
    }

    public function getPost(?string $name = null, mixed $filters = null, mixed $defaultValue = null, bool $notAllowEmpty = false, bool $noRecursive = false): mixed
    {
        $contentType = $this->getContentType();

        if (stripos($contentType, 'json')) {
            $data = $this->getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        } else {
            $data = parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
        }

        return $data;
    }

    public function getClientAddress(bool $trustForwardedHeader = true): bool|string
    {
        $address = $this->getServer('REMOTE_ADDR');

        if ($trustForwardedHeader) {
            if (!empty($this->getServer('HTTP_X_FORWARDED_FOR'))) {
                list($address) = explode(',', $this->getServer('HTTP_X_FORWARDED_FOR'));
            }
        }

        return is_string($address) ? trim($address) : false;
    }

}
