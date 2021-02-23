<?php

namespace App\Console\Tasks;

use App\Library\Utils\Password as PasswordUtil;
use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;

class ResetDemoAccountTask extends Task
{

    public function mainAction()
    {
        $this->reset_account_100015();
        $this->reset_account_100065();
    }

    protected function reset_account_100015()
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById(100015);

        $salt = PasswordUtil::salt();
        $hash = PasswordUtil::hash('123456', $salt);

        $account->phone = '13507083515';
        $account->email = '100015@163.com';
        $account->salt = $salt;
        $account->password = $hash;

        $account->update();

        $userRepo = new UserRepo();

        $user = $userRepo->findById(100015);

        $user->name = '酷瓜云课堂';
        $user->title = '页面重构设计';
        $user->about = '酷瓜云课堂（腾讯云版），依托腾讯云基础服务架构，采用C扩展PHP框架Phalcon开发，致力开源网课系统，开源网校系统，开源在线教育系统。';
        $user->avatar = '/img/avatar/20210214084718217596.jpeg';

        $user->update();
    }

    protected function reset_account_100065()
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById(100065);

        $salt = PasswordUtil::salt();
        $hash = PasswordUtil::hash('123456', $salt);

        $account->phone = '13607083515';
        $account->email = '100065@163.com';
        $account->salt = $salt;
        $account->password = $hash;

        $account->update();

        $userRepo = new UserRepo();

        $user = $userRepo->findById(100065);

        $user->name = 'Murphy';
        $user->title = '移动开发工程师';
        $user->about = '腾讯开放平台高级工程师 Web技术大牛 10年风雨开发路 腾讯学院讲师 沙龙论坛演讲大V T恤男 儒雅先生 精通C#、ASP．NET和SQL Server等 负责开发“QQ概念版”、“Q+”、“影视中心”、“QQ互联”等产品';
        $user->avatar = '/img/avatar/202001251155458851.jpg';

        $user->update();
    }

}
