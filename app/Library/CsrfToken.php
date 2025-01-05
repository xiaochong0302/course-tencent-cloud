<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

use Phalcon\Config;
use Phalcon\Crypt;
use Phalcon\Di;
use Phalcon\Text;

class CsrfToken
{

    /**
     * @var Crypt
     */
    protected $crypt;

    protected $lifetime = 86400;

    protected $delimiter = '@@';

    protected $fixed = 'KG';

    public function __construct()
    {
        $this->crypt = Di::getDefault()->get('crypt');
    }

    public function getToken()
    {
        $content = [
            $this->getExpiredTime(),
            $this->fixed,
            Text::random(Text::RANDOM_ALNUM, 8),
        ];

        $text = implode($this->delimiter, $content);

        return $this->crypt->encryptBase64($text);
    }

    public function checkToken($token)
    {
        if (!$token) return false;

        $text = $this->crypt->decryptBase64($token);

        $params = explode($this->delimiter, $text);

        if (count($params) != 3) {
            return false;
        }

        if ($params[0] < time() || $params[1] != $this->fixed || strlen($params[2]) != 8) {
            return false;
        }

        return true;
    }

    protected function getExpiredTime()
    {
        /**
         * @var $config Config
         */
        $config = Di::getDefault()->getShared('config');

        $lifetime = $config->path('csrf_token.lifetime', $this->lifetime);

        return $lifetime + time();
    }

}
