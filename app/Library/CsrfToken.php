<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library;

use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Encryption\Crypt;
use Phalcon\Support\Helper\Str\Random;
use Phalcon\Support\HelperFactory;

class CsrfToken
{

    /**
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    /**
     * @var string
     */
    protected string $delimiter = '@@';

    /**
     * @var string
     */
    protected string $fixed = 'KG';

    public function __construct()
    {
        $this->crypt = Di::getDefault()->get('crypt');
    }

    public function getToken(): string
    {
        $helper = new HelperFactory();

        $content = [
            $this->getExpiredTime(),
            $this->fixed,
            $helper->random(Random::RANDOM_ALNUM, 8),
        ];

        $text = implode($this->delimiter, $content);

        return $this->crypt->encryptBase64($text);
    }

    public function checkToken(string $token): bool
    {
        if (strlen($token) == 0) return false;

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

    protected function getExpiredTime(): int
    {
        /**
         * @var $config Config
         */
        $config = Di::getDefault()->getShared('config');

        $lifetime = $config->path('csrf_token.lifetime', $this->lifetime);

        return $lifetime + time();
    }

}
