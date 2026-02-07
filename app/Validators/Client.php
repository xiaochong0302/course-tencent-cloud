<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\KgClient as KgClientModel;

class Client extends Validator
{

    public function checkH5Platform($platform)
    {
        $platform = strtoupper($platform);

        if ($platform == 'H5') {
            return KgClientModel::TYPE_H5;
        }

        throw new BadRequestException('client.invalid_type');
    }

    public function checkMpPlatform($platform)
    {
        $platform = strtoupper($platform);

        if ($platform == 'MP-WEIXIN') {
            return KgClientModel::TYPE_MP_WEIXIN;
        } elseif ($platform == 'MP-ALIPAY') {
            return KgClientModel::TYPE_MP_ALIPAY;
        } else {
            throw new BadRequestException('client.invalid_type');
        }
    }

    public function checkAppPlatform($platform)
    {
        $platform = strtoupper($platform);

        if ($platform == 'APP-PLUS') {
            return KgClientModel::TYPE_APP;
        }

        throw new BadRequestException('client.invalid_type');
    }

}
