<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Verify;

use App\Services\Logic\Service as LogicService;
use App\Validators\Verify as VerifyValidator;

class Ticket extends LogicService
{

    public function handle()
    {
        $rand = $this->request->getPost('rand', ['trim', 'string']);

        $validator = new VerifyValidator();

        $rand = $validator->checkRand($rand);

        return $this->crypt->encryptBase64($rand);
    }

}
