<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use App\Models\ImGroup as GroupModel;
use App\Models\User as UserModel;
use Phalcon\Di\Injectable;

class GroupDocument extends Injectable
{

    /**
     * 设置文档
     *
     * @param GroupModel $group
     * @return \XSDocument
     */
    public function setDocument(GroupModel $group)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($group);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param GroupModel $group
     * @return array
     */
    public function formatDocument(GroupModel $group)
    {
        $owner = '{}';

        if ($group->owner_id > 0) {
            $record = UserModel::findFirst($group->owner_id);
            $owner = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $group->avatar = GroupModel::getAvatarPath($group->avatar);

        return [
            'id' => $group->id,
            'type' => $group->type,
            'name' => $group->name,
            'avatar' => $group->avatar,
            'about' => $group->about,
            'user_count' => $group->user_count,
            'owner' => $owner,
        ];
    }

}
