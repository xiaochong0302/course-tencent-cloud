<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Services;

use App\Models\Connect as ConnectModel;
use App\Models\User as UserModel;
use App\Repos\Connect as ConnectRepo;
use App\Repos\User as UserRepo;
use App\Services\Auth\Api as ApiAuthService;
use App\Services\Logic\Notice\External\AccountLogin as AccountLoginNoticeService;
use App\Services\OAuth\QQ as QQAuth;
use App\Services\OAuth\WeChat as WeChatAuth;
use App\Services\OAuth\WeiBo as WeiBoAuth;
use Exception;

class Connect extends Service
{

    public function getWechatInfo()
    {
        return [
            'auth_url' => kg_full_url(['for' => 'api.oauth.wechat']),
            'auto_login' => 1,
        ];
    }

    public function getWechatRedirectCache()
    {
        return $this->session->get('wechat_redirect');
    }

    public function setWechatRedirectCache($redirect)
    {
        $this->session->set('wechat_redirect', $redirect);
    }

    public function removeWechatRedirectCache()
    {
        $this->session->remove('wechat_redirect');
    }

    public function handleCallback($provider)
    {
        $code = $this->request->getQuery('code');
        $state = $this->request->getQuery('state');

        $openUser = $this->getOpenUserInfo($code, $state, $provider);
        $relation = $this->getConnectRelation($openUser['id'], $provider);

        $token = null;

        if ($relation) {

            $relation->update_time = time();

            $relation->update();

            $userRepo = new UserRepo();

            $user = $userRepo->findById($relation->user_id);

            $auth = new ApiAuthService();

            $token = $auth->saveAuthInfo($user);

            $this->handleLoginNotice($user);
        }

        return [
            'openid' => $openUser['id'],
            'token' => $token,
        ];
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
            case ConnectModel::PROVIDER_WEIBO:
                $auth = $this->getWeiBoAuth();
                break;
            case ConnectModel::PROVIDER_WECHAT_OA:
                $auth = $this->getWeChatAuth();
                break;
        }

        if (!$auth) {
            throw new Exception('Invalid OAuth Provider');
        }

        return $auth;
    }

    protected function getQQAuth()
    {
        $settings = $this->getSettings('oauth.qq');

        $settings['redirect_uri'] = kg_full_url(['for' => 'api.oauth.qq_callback']);

        return new QQAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getWeChatAuth()
    {
        /**
         * 使用的是微信公众号网页授权登录功能
         */
        $settings = $this->getSettings('wechat.oa');

        $settings['redirect_uri'] = kg_full_url(['for' => 'api.oauth.wechat_callback']);

        return new WeChatAuth(
            $settings['app_id'],
            $settings['app_secret'],
            $settings['redirect_uri']
        );
    }

    protected function getWeiBoAuth()
    {
        $settings = $this->getSettings('oauth.weibo');

        $settings['redirect_uri'] = kg_full_url(['for' => 'api.oauth.weibo_callback']);

        return new WeiBoAuth(
            $settings['client_id'],
            $settings['client_secret'],
            $settings['redirect_uri']
        );
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

            if ($connect->deleted == 1) {
                $connect->deleted = 0;
            }

            $connect->update();

        } else {

            $connect = new ConnectModel();

            $connect->user_id = $user->id;
            $connect->open_id = $openUser['id'];
            $connect->open_name = $openUser['name'];
            $connect->open_avatar = $openUser['avatar'];
            $connect->provider = $openUser['provider'];

            $connect->create();
        }
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $service = new AccountLoginNoticeService();

        $service->createTask($user);
    }

}
