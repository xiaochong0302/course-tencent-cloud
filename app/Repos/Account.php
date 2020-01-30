<?php

namespace App\Repos;

use App\Models\Account as AccountModel;

class Account extends Repository
{

    /**
     * @param int $id
     * @return AccountModel
     */
    public function findById($id)
    {
        $result = AccountModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $phone
     * @return AccountModel
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
     * @return AccountModel
     */
    public function findByEmail($email)
    {
        $result = AccountModel::findFirst([
            'conditions' => 'email = :email:',
            'bind' => ['email' => $email],
        ]);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = AccountModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

}
