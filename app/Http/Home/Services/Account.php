<?php

namespace App\Http\Home\Services;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Validators\Account as AccountFilter;
use App\Repos\Account as AccountRepo;
use App\Repos\Captcha as CaptchaRepo;
use App\Repos\User as UserRepo;

class Account extends Service
{

    public function signup()
    {
        $post = $this->request->getPost();

        $filter = new AccountFilter();

        $mobile = $filter->checkMobile($post['mobile']);
        $password = $filter->checkPassword($post['password']);
        $code = $filter->checkCode($post['code']);

        $captcha = $filter->checkCaptcha($mobile, $code);

        $filter->checkIfMobileTaken($mobile);

        $accountRepo = new AccountRepo();

        $account = $accountRepo->create([
            'mobile' => $mobile,
            'password' => $this->security->hash($password),
        ]);

        $userRepo = new UserRepo();

        $userRepo->create([
            'id' => $account->id,
            'name' => 'user_' . sprintf('%06d', $account->id),
        ]);

        $this->clearCaptcha($captcha);

        $this->setAuthToken($account);
    }

    public function login()
    {
        $post = $this->request->getPost();

        $filter = new AccountFilter();

        $mobile = $filter->checkMobile($post['mobile']);
        $password = $filter->checkPassword($post['password']);

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findByMobile($mobile);

        if (!$account) {
            throw new UnauthorizedException('account.login_mobile_incorrect');
        }
        
        $passwordOk = $this->security->checkHash($password, $account->password);

        if (!$passwordOk) {
            throw new UnauthorizedException('account.login_password_incorrect');
        }

        $this->setAuthToken($account);
    }

    public function logout()
    {
        if ($this->cookies->has('token')) {
            $this->cookies->get('token')->delete();
        }
    }

    public function resetPassword()
    {
        $post = $this->request->getPost();

        $filter = new AccountFilter();

        $mobile = $filter->checkMobile($post['mobile']);
        $newPassword = $filter->checkPassword($post['new_password']);
        $code = $filter->checkCode($post['code']);

        $filter->checkConfirmPassword($post['new_password'], $post['confirm_password']);

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findByMobile($mobile);

        if (!$account) {
            throw new BadRequestException('account.not_found');
        }

        $captcha = $filter->checkCaptcha($mobile, $code);

        $account->password = $this->security->hash($newPassword);

        $account->update();

        $this->clearCaptcha($captcha);
    }

    public function updateMobile()
    {
        $user = $this->getLoggedUser();

        $account = $this->findOrFail($user->id);

        $post = $this->request->getPost();

        $filter = new AccountFilter();

        $filter->checkOriginPassword($post['origin_password'], $account->password);

        $mobile = $filter->checkMobile($post['mobile']);
        $code = $filter->checkCode($post['code']);

        if ($mobile != $account->mobile) {
            $filter->checkIfMobileTaken($mobile);
        }

        $captcha = $filter->checkCaptcha($mobile, $code);

        $account->mobile = $mobile;

        $account->update();

        $this->clearCaptcha($captcha);
    }

    public function updatePassword()
    {
        $user = $this->getLoggedUser();

        $account = $this->findOrFail($user->id);

        $post = $this->request->getPost();

        $filter = new AccountFilter();

        $filter->checkOriginPassword($post['origin_password'], $account->password);

        $newPassword = $filter->checkPassword($post['new_password']);

        $filter->checkConfirmPassword($post['new_password'], $post['confirm_password']);

        $account->password = $this->security->hash($newPassword);

        $account->update();
    }

    public function sendCaptcha()
    {
        $post = $this->request->getPost();

        $filter = new AccountFilter();

        $mobile = $filter->checkMobile($post['mobile']);

        $captchaRepo = new CaptchaRepo();

        $oldCaptcha = $captchaRepo->findLastByMobile($mobile);

        if ($oldCaptcha) {
            if (time() + 120 < $oldCaptcha->expire_time) {
                return $oldCaptcha;
            }
        }

        $clientIp = $this->request->getClientAddress();

        $fromTime = time() - 3600;

        $filter->checkCaptchaLimit($clientIp, $fromTime);

        $captcha = $captchaRepo->create([
            'mobile' => $mobile,
            'code' => rand(1000, 9999),
            'client_ip' => $clientIp,
            'expire_time' => time() + 600,
        ]);

        return $captcha;
    }

    private function findOrFail($id)
    {
        $repo = new AccountRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function clearCaptcha($captcha)
    {
        $captcha->expire_time = 0;

        $captcha->update();
    }

    private function setAuthToken($account)
    {
        $this->cookies->set('token', $account->id, strtotime('+1 month'));
    }

}
