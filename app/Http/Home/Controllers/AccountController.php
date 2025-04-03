<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Account as AccountService;
use App\Services\Logic\Account\EmailUpdate as EmailUpdateService;
use App\Services\Logic\Account\OAuthProvider as OAuthProviderService;
use App\Services\Logic\Account\PasswordReset as PasswordResetService;
use App\Services\Logic\Account\PasswordUpdate as PasswordUpdateService;
use App\Services\Logic\Account\PhoneUpdate as PhoneUpdateService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends Controller
{

    /**
     * @Get("/register", name="home.account.register")
     */
    public function registerAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getAccountRegisterUrl();
            return $this->response->redirect($location);
        }

        if ($this->authUser->id > 0) {
            return $this->response->redirect(['for' => 'home.index']);
        }

        $returnUrl = $this->request->getHTTPReferer();

        $service = new OAuthProviderService();

        $oauthProvider = $service->handle();

        $this->seo->prependTitle('用户注册');

        $this->view->setVar('return_url', $returnUrl);
        $this->view->setVar('local_oauth', $oauthProvider['local']);
    }

    /**
     * @Get("/login", name="home.account.login")
     */
    public function loginAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getAccountLoginUrl();
            return $this->response->redirect($location);
        }

        if ($this->authUser->id > 0) {
            return $this->response->redirect(['for' => 'home.index']);
        }

        $service = new OAuthProviderService();

        $oauthProvider = $service->handle();

        $returnUrl = $this->request->getHTTPReferer();

        $this->seo->prependTitle('用户登录');

        $this->view->setVar('oauth_provider', $oauthProvider);
        $this->view->setVar('return_url', $returnUrl);
    }

    /**
     * @Get("/logout", name="home.account.logout")
     */
    public function logoutAction()
    {
        $service = new AccountService();

        $service->logout();

        return $this->response->redirect(['for' => 'home.index']);
    }

    /**
     * @Get("/forget", name="home.account.forget")
     */
    public function forgetAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getAccountForgetUrl();
            return $this->response->redirect($location);
        }

        if ($this->authUser->id > 0) {
            return $this->response->redirect(['for' => 'home.index']);
        }

        $this->seo->prependTitle('忘记密码');
    }

    /**
     * @Post("/register", name="home.account.do_register")
     */
    public function doRegisterAction()
    {
        $service = new AccountService();

        $service->register();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $content = [
            'location' => $location,
            'msg' => '注册账号成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/login", name="home.account.pwd_login")
     */
    public function loginByPasswordAction()
    {
        $service = new AccountService();

        $service->loginByPassword();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $content = [
            'location' => $location,
            'msg' => '登录账号成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/verify/login", name="home.account.verify_login")
     */
    public function loginByVerifyAction()
    {
        $service = new AccountService();

        $service->loginByVerify();

        $returnUrl = $this->request->getPost('return_url', 'string');

        $location = $returnUrl ?: $this->url->get(['for' => 'home.index']);

        $content = [
            'location' => $location,
            'msg' => '登录账号成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/reset", name="home.account.reset_pwd")
     */
    public function resetPasswordAction()
    {
        $service = new PasswordResetService();

        $service->handle();

        $loginUrl = $this->url->get(['for' => 'home.account.login']);

        $content = [
            'location' => $loginUrl,
            'msg' => '重置密码成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/phone/update", name="home.account.update_phone")
     */
    public function updatePhoneAction()
    {
        $service = new PhoneUpdateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.account']);

        $content = [
            'location' => $location,
            'msg' => '更新手机成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/email/update", name="home.account.update_email")
     */
    public function updateEmailAction()
    {
        $service = new EmailUpdateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.account']);

        $content = [
            'location' => $location,
            'msg' => '更新邮箱成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/password/update", name="home.account.update_pwd")
     */
    public function updatePasswordAction()
    {
        $service = new PasswordUpdateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.account']);

        $content = [
            'location' => $location,
            'msg' => '更新密码成功',
        ];

        return $this->jsonSuccess($content);
    }

}
