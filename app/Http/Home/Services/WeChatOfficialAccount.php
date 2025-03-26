<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Connect as ConnectModel;
use App\Models\User as UserModel;
use App\Repos\Connect as ConnectRepo;
use App\Repos\User as UserRepo;
use App\Services\Auth\Home as AuthService;
use App\Services\Logic\Account\Register as RegisterService;
use App\Services\Logic\Notice\External\AccountLogin as AccountLoginNotice;
use App\Services\Logic\WeChat\OfficialAccount as WeChatOAService;
use App\Validators\Account as AccountValidator;
use App\Validators\WeChatOfficialAccount as WeChatOAValidator;

class WeChatOfficialAccount extends Service
{

    public function authLogin()
    {
        $ticket = $this->request->getPost('ticket');

        $validator = new WeChatOAValidator();

        $openId = $validator->checkLoginOpenId($ticket);

        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openId, ConnectModel::PROVIDER_WECHAT_OA);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($connect->user_id);

        $validator = new AccountValidator();

        $validator->checkIfAllowLogin($user);

        $connect->update_time = time();

        $connect->update();

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);
    }

    public function bindLogin()
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $validator = new WeChatOAValidator();

        $openId = $validator->checkLoginOpenId($post['ticket']);

        $unionId = $this->getUnionId($openId);

        $connect = new ConnectModel();

        $connect->user_id = $user->id;
        $connect->open_id = $openId;
        $connect->union_id = $unionId;
        $connect->provider = ConnectModel::PROVIDER_WECHAT_OA;

        $connect->create();

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);
    }

    public function bindRegister()
    {
        $post = $this->request->getPost();

        $validator = new WeChatOAValidator();

        $openId = $validator->checkLoginOpenId($post['ticket']);

        $unionId = $this->getUnionId($openId);

        $registerService = new RegisterService();

        $account = $registerService->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $connect = new ConnectModel();

        $connect->user_id = $user->id;
        $connect->open_id = $openId;
        $connect->union_id = $unionId;
        $connect->provider = ConnectModel::PROVIDER_WECHAT_OA;

        $connect->create();

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterRegister', $this, $user);
    }

    protected function getUnionId($openId)
    {
        $service = new WeChatOAService();

        $app = $service->getOfficialAccount();

        $user = $app->user->get($openId);

        return $user['unionid'] ?: '';
    }

    protected function getAppAuth()
    {
        /**
         * @var $auth AuthService
         */
        $auth = $this->getDI()->get('auth');

        return $auth;
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $notice = new AccountLoginNotice();

        $notice->createTask($user);
    }

}
