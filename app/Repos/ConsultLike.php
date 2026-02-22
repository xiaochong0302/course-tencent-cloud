<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\ConsultLike as ConsultLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ConsultLike extends Repository
{

    /**
     * @param int $consultId
     * @param int $userId
     * @return ConsultLikeModel|Model|bool
     */
    public function findConsultLike($consultId, $userId)
    {
        return ConsultLikeModel::findFirst([
            'conditions' => 'consult_id = :consult_id: AND user_id = :user_id:',
            'bind' => ['consult_id' => $consultId, 'user_id' => $userId],
        ]);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function findUserLikedConsultIds($userId)
    {
        $result = [];

        /**
         * @var Resultset $rows
         */
        $rows =  ConsultLikeModel::query()
            ->columns(['consult_id'])
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'consult_id');
        }

        return $result;
    }

}
