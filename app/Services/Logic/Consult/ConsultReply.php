<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\Notice\External\ConsultReply as ConsultReplyNotice;
use App\Services\Logic\Service as LogicService;
use App\Validators\Consult as ConsultValidator;

class ConsultReply extends LogicService
{

    use ConsultTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $consult = $this->checkConsult($id);

        $validator = new ConsultValidator();

        $validator->checkReplyPriv($consult, $user);

        $answer = $validator->checkAnswer($post['answer']);

        $consult->replier_id = $user->id;
        $consult->reply_time = time();
        $consult->answer = $answer;
        $consult->update();

        if ($consult->reply_time == 0) {
            $this->handleReplyNotice($consult);
        }

        $this->eventsManager->fire('Consult:afterReply', $this, $consult);

        return $consult;
    }

    protected function handleReplyNotice(ConsultModel $consult)
    {
        $notice = new ConsultReplyNotice();

        $notice->createTask($consult);
    }

}
