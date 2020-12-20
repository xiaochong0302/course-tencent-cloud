<?php

namespace App\Services\Sms\Notice;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class LiveBegin extends Smser
{

    protected $templateCode = 'live_begin';

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

        $params = [
            $params['course']['title'],
            $params['chapter']['title'],
            date('H:i', $params['live']['start_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($account->phone, $templateId, $params);
    }

}
