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
        $text = implode($this->delimiter, [time(), $this->fixed, Text::random(8)]);

        return $this->crypt->encryptBase64($text);
    }

    public function checkToken($token)
    {
        if (!$token) return false;

        $text = $this->crypt->decryptBase64($token);

        $params = explode($this->delimiter, $text);

        if (!isset($params[0]) || !isset($params[1]) || !isset($params[2])) {
            return false;
        }

        if ($params[0] != intval($params[0]) || $params[1] != $this->fixed || strlen($params[2]) != 8) {
            return false;
        }

        if (time() - $params[0] > $this->lifetime) {
            return false;
        }

        return true;
    }

}