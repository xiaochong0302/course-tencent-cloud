<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImUser as ImUserRepo;

class ImUser extends Validator
{

    public function checkUser($id)
    {
        $userRepo = new ImUserRepo();

        $user = $userRepo->findById($id);

        if (!$user) {
            throw new BadRequestException('im_user.not_found');
        }

        return $user;
    }

    public function checkSign($sign)
    {
        $value = $this->filter->sanitize($sign, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('im_user.sign_too_long');
        }

        return $value;
    }

    public function checkSkin($url)
    {
        if (empty($url)) {
            throw new BadRequestException('im_user.invalid_skin');
        }

        return $url;
    }

    public function checkStatus($status)
    {
        if (!in_array($status, ['online', 'hide'])) {
            throw new BadRequestException('im_user.invalid_status');
        }

        return $status;
    }

}
