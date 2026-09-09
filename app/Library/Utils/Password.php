<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Utils;

class Password
{

    public static function salt(): string
    {
        return uniqid();
    }

    public static function hash(string $password, string $salt): string
    {
        return md5(md5($password) . md5($salt));
    }

    public static function checkHash(string $password, string $salt, string $passwordHash): bool
    {
        $inputHash = self::hash($password, $salt);

        return $inputHash == $passwordHash;
    }

}
