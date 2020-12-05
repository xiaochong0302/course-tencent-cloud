<?php

namespace App\Http\Home\Services;

use App\Library\OAuth\QQ as QQAuth;
use App\Library\OAuth\WeiBo as WeiBoAuth;
use App\Library\OAuth\WeiXin as WeiXinAuth;
use App\Models\Connect as ConnectModel;
use App\Models\User as UserModel;
use App\Repos\Connect as ConnectRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Account\Register as RegisterService;
use App\Validators\Account as AccountValidator;

class Connect extends Service
{

    public function bindLogin()
    {
        $post = $this->request->getPost();

        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($post['account'], $post['password']);

        $openUser = $this->getOpenUserInfo($post['code'], $post['stats'], $post['provider']);

        $this->handleBindRelation($user, $openUser, $post['provider']);

        $this->auth->saveAuthInfo($user);
    }

    public function bindRegister()
    {
        $post = $this->request->getPost();

        $openUser = $this->getOpenUserInfo($post['code'], $post['state'], $post['provider']);

        $registerService = new RegisterService();

        $account = $registerService->handle();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $this->handleBindRelation($user, $openUser, $post['provider']);

        $this->auth->saveAuthInfo($user);
    }

    public function bindUser($provider)
    {
        $code = $this->request->getQuery('code', 'trim');
        $state = $this->request->getQuery('state', 'trim');

        $user = $this->getLoginUser();

        $openUser = $this->getOpenUserInfo($code, $state, $provider);

        $this->handleBindRelation($user, $openUser, $provider);
    }

    public function getAuthorizeUrl($provider)
    {
        $auth = $this->getAuth($provider);

        return $auth->getAuthorizeUrl();
    }

    public function getAuth($provider)
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

    protected function getOpenUserInfo($code, $state, $provider)
    {
        $auth = $this->getAuth($provider);

        $auth->checkState($state);

        $token = $auth->getAccessToken($code);

        $openId = $auth->getOpenId($token);

        return $auth->getUserInfo($token, $openId);
    }

    protected function handleBindRelation(UserModel $user, array $openUser, $provider)
    {
        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openUser['id'], $provider);

        if ($connect) {

            if ($connect->deleted == 1) {
                $connect->deleted = 0;
                $connect->update();
            }

        } else {

            $connect = new ConnectModel();

            $connect->user_id = $user->id;
            $connect->open_id = $openUser['id'];
            $connect->open_name = $openUser['name'];
            $connect->open_avatar = $openUser['avatar'];
            $connect->provider = $provider;

            $connect->create();
        }
    }

}
