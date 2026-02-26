<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Account as AccountModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class Register extends LogicService
{

    use LoginFieldTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $post = $this->handleLoginFields($post);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($post['account'], $post['verify_code']);

        $accountValidator = new AccountValidator();

        $accountValidator->checkRegisterStatus($post['account']);

        $accountValidator->checkLoginName($post['account']);

        $data = [];

        if (CommonValidator::phone($post['account'])) {

            $data['phone'] = $accountValidator->checkPhone($post['account']);

            $accountValidator->checkIfPhoneTaken($post['account']);

        } elseif (CommonValidator::email($post['account'])) {

            $data['email'] = $accountValidator->checkEmail($post['account']);

            $accountValidator->checkIfEmailTaken($post['account']);
        }

        $data['password'] = $accountValidator->checkPassword($post['password']);

        $data['salt'] = PasswordUtil::salt();

        $data['password'] = PasswordUtil::hash($data['password'], $data['salt']);

        try {

            $this->db->begin();

            $account = new AccountModel();

            $account->create($data);

            $this->db->commit();

            return $account;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger();

            $logger->error('Register Account Exception: ' . kg_json_encode([
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
