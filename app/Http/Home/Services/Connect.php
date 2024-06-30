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
use App\Services\OAuth\QQ as QQAuth;
use App\Services\OAuth\WeiBo as WeiBoAuth;
use App\Services\OAuth\WeiXin as WeiXinAuth;
use App\Validators\Account as AccountValidator;

class Connect extends Service
{

    public function bindLogin()
    {
        $post = $this->request->getPost();

        $auth = $this->getConnectAuth($post['provider']);

        $auth->checkState($post['state']);

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $openUser = json_decode($post['open_user'], true);

        $this->handleConnectRelation($user, $openUser);

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);
    }

    public function bindRegister()
    {
        $post = $this->request->getPost();

        $auth = $this->getConnectAuth($post['provider']);

        $auth->checkState($post['state']);

        $openUser = json_decode($post['open_user'], true);

        $registerService = new RegisterService();

        $account = $registerService->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $this->handleConnectRelation($user, $openUser);

        $this->handleLoginNotice($user);

        $auth = $this->getAppAuth();

        $auth->saveAuthInfo($user);

        $this->eventsManager->fire('Account:afterRegister', $this, $user);
    }

    public function bindUser(array $openUser)
    {
        $user = $this->getLoginUser();

        $this->handleConnectRelation($user, $openUser);
    }

    public function authConnectLogin(ConnectModel $connect)
    {
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

    public function getAuthorizeUrl($provider)
    {
        $auth = $this->getConnectAuth($provider);

        return $auth->getAuthorizeUrl();
    }

    public function getOpenUserInfo($code, $state, $provider)
    {
        $auth = $this->getConnectAuth($provider);

        $auth->checkState($state);

        $token = $auth->getAccessToken($code);

        $openId = $auth->getOpenId($token);

        return $auth->getUserInfo($token, $openId);
    }

    public function getConnectRelation($openId, $provider)
    {
        $connectRepo = new ConnectRepo();

        return $connectRepo->findByOpenId($openId, $provider);
    }

    public function getConnectAuth($provider)
    {
        $auth = null;

        switch ($provider) {
            case ConnectModel::PROVIDER_QQ:
                $auth = $this->getQQAuth();
                break;
            case ConnectModel::PROVIDER_WEIXIN:
                $auth = $this->getWeiXinAuth();
                break;
            case ConnectModel::PROVIDER_WEIBO:
                $auth = $this->getWeiBoAuth();
                break;
        }

        if (!$auth) {
            throw new \Exception('Invalid OAuth Provider');
        }

        return $auth;
    }

    protected function getQQAuth()
    {
        $settings = $this->getSettings('oauth.qq');

        return new QQAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getWeiXinAuth()
    {
        $settings = $this->getSettings('oauth.weixin');

        return new WeiXinAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getWeiBoAuth()
    {
        $settings = $this->getSettings('oauth.weibo');

        return new WeiBoAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getAppAuth()
    {
        /**
         * @var $auth AuthService
         */
        $auth = $this->getDI()->get('auth');

        return $auth;
    }

    protected function handleConnectRelation(UserModel $user, array $openUser)
    {
        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openUser['id'], $openUser['provider']);

        if ($connect) {

            $connect->open_name = $openUser['name'];
            $connect->open_avatar = $openUser['avatar'];

            if ($connect->user_id != $user->id) {
                $connect->user_id = $user->id;
            }

            if (empty($connect->union_id) && !empty($openUser['unionid'])) {
                $connect->union_id = $openUser['unionid'];
            }

            if ($connect->deleted == 1) {
                $connect->deleted = 0;
            }

            $connect->update();

        } else {

            $connect = new ConnectModel();

            $connect->user_id = $user->id;
            $connect->union_id = $openUser['unionid'];
            $connect->open_id = $openUser['id'];
            $connect->open_name = $openUser['name'];
            $connect->open_avatar = $openUser['avatar'];
            $connect->provider = $openUser['provider'];

            $connect->create();
        }
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $notice = new AccountLoginNotice();

        $notice->createTask($user);
    }

}
