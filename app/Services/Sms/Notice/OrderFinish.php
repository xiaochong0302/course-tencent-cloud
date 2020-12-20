<?php

namespace App\Services\Sms\Notice;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class OrderFinish extends Smser
{

    protected $templateCode = 'order_finish';

    /**
     * @param UserModel $user
     * @param array $params
     * @return bool|null
     */
    public function handle(UserModel $user, array $params)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        if (!$account->phone) return null;

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['order']['subject'],
            $params['order']['sn'],
            $params['order']['amount'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
