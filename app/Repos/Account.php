<?php

namespace App\Repos;

use App\Models\Account as AccountModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Account extends Repository
{

    /**
     * @param int $id
     * @return AccountModel|Model|bool
     */
    public function findById($id)
    {
        return AccountModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $phone
     * @return AccountModel|Model|bool
     */
    public function findByPhone($phone)
    {
        return AccountModel::findFirst([
            'conditions' => 'phone = :phone:',
            'bind' => ['phone' => $phone],
        ]);
    }

    /**
     * @param string $email
     * @return AccountModel|Model|bool
     */
    public function findByEmail($email)
    {
        return AccountModel::findFirst([
            'conditions' => 'email = :email:',
            'bind' => ['email' => $email],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|AccountModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return AccountModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
