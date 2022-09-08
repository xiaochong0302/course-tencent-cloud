<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Mail;

use App\Services\Mailer;

class Test extends Mailer
{

    /**
     * @param string $email
     * @return bool
     */
    public function handle($email)
    {
        try {

            $message = $this->manager->createMessage();

            $count = $message->to($email)
                ->subject('测试邮件')
                ->content('东风快递，使命必达')
                ->send();

            $result = $count > 0;

        } catch (\Exception $e) {

            $this->logger->error('Send Test Mail Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

}
