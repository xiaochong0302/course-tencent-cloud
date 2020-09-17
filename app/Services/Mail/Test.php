<?php

namespace App\Services\Mail;

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
