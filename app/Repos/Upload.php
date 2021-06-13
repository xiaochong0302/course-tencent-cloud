<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Upload as UploadModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Upload extends Repository
{

    /**
     * @param int $id
     * @return UploadModel|Model|bool
     */
    public function findById($id)
    {
        return UploadModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $md5
     * @return UploadModel|Model|bool
     */
    public function findByMd5($md5)
    {
        return UploadModel::findFirst([
            'conditions' => 'md5 = :md5:',
            'bind' => ['md5' => $md5],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|UploadModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return UploadModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
