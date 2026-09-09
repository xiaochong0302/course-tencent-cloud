<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Account as AccountModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Account extends Repository
{

    /**
     * @param int $id
     * @return AccountModel|Row|null
     */
    public function findById(int $id)
    {
        return AccountModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $phone
     * @return AccountModel|Row|null
     */
    public function findByPhone(string $phone)
    {
        return AccountModel::findFirst([
            'conditions' => 'phone = :phone:',
            'bind' => ['phone' => $phone],
        ]);
    }

    /**
     * @param string $email
     * @return AccountModel|Row|null
     */
    public function findByEmail(string $email)
    {
        return AccountModel::findFirst([
            'conditions' => 'email = :email:',
            'bind' => ['email' => $email],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return Resultset|ResultsetInterface|AccountModel[]
     */
    public function findByIds(array $ids, array|string $columns = '*')
    {
        return AccountModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param array $ids
     * @return ResultsetInterface|Resultset|AccountModel[]
     */
    public function findShallowAccountByIds(array $ids)
    {
        return AccountModel::query()
            ->columns(['id', 'email', 'phone'])
            ->inWhere('id', $ids)
            ->execute();
    }

}
