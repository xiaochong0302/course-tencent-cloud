<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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