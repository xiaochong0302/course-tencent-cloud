<?php

namespace App\Library;

use Phalcon\Crypt;
use Phalcon\Di;
use Phalcon\Text;

class CsrfToken
{

    /**
     * @var Crypt
     */
    protected $crypt;

    protected $lifetime = 600;

    protected $delimiter = '@@';

    protected $fixed = 'KG';

    public function __construct()
    {
        $this->crypt = Di::getDefault()->get('crypt');
    }

    public function getToken()
    {
        $content = [
            time() + $this->lifetime,
            $this->fixed,
            Text::random(8),
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

}