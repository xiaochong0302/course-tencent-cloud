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
        $text = $this->crypt->decryptBase64($token);

        list($time, $fixed, $random) = explode($this->delimiter, $text);

        if ($time != intval($time) || $fixed != $this->fixed || strlen($random) != 8) {
            return false;
        }

        if (time() - $time > $this->lifetime) {
            return false;
        }

        return true;
    }

}