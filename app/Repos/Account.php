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
        $result = AccountModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $phone
     * @return AccountModel|Model|bool
     */
    public function findByPhone($phone)
    {
        $result = AccountModel::findFirst([
            'conditions' => 'phone = :phone:',
            'bind' => ['phone' => $phone],
        ]);

        return $result;
    }

    /**
     * @param string $email
     * @return AccountModel|Model|bool
     */
    public function findByEmail($email)
    {
        $result = AccountModel::findFirst([
            'conditions' => 'email = :email:',
            'bind' => ['email' => $email],
        ]);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|AccountModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = AccountModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

}
