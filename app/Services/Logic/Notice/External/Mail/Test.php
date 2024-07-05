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

    public function handle($email)
    {
        $subject = $this->formatSubject('测试邮件');
        $content = $this->formatContent('东风快递，使命必达');

        return $this->send($email, $subject, $content);
    }

}
