<?php

namespace App\Repos;

use App\Models\Account as AccountModel;
use App\Models\AccountBind as AccountBindModel;
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
        return AccountModel::findFirst($id);
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
     * @param string $provider
     * @param string $openId
     * @return AccountModel|Model|bool
     */
    public function findByOpenId($provider, $openId)
    {
        $bind = AccountBindModel::findFirst([
            'conditions' => 'provider = ?1 AND open_id = ?2',
            'bind' => [1 => $provider, 2 => $openId],
        ]);

        if (!$bind) return false;

        return AccountModel::findFirst($bind->user_id);
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
