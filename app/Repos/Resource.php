<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Resource as ResourceModel;
use Phalcon\Mvc\Model;

class Resource extends Repository
{

    /**
     * @param int $id
     * @return ResourceModel|Model|bool
     */
    public function findById($id)
    {
        return ResourceModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

}
