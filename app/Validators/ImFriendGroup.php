<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImFriendGroup as ImFriendGroupRepo;

class ImFriendGroup extends Validator
{

    public function checkGroup($id)
    {
        $groupRepo = new ImFriendGroupRepo();

        $group = $groupRepo->findById($id);

        if (!$group) {
            throw new BadRequestException('im_friend_group.not_found');
        }

        return $group;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('im_friend_group.name_too_short');
        }

        if ($length > 15) {
            throw new BadRequestException('im_friend_group.name_too_long');
        }

        return $value;
    }

}
