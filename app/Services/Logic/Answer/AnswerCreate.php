<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Notice\System\QuestionAnswered as QuestionAnsweredNotice;
use App\Services\Logic\Point\History\AnswerPost as AnswerPostPointHistory;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Answer as AnswerValidator;
use App\Validators\UserLimit as UserLimitValidator;

class AnswerCreate extends LogicService
{

    use QuestionTrait;
    use AnswerTrait;
    use AnswerDataTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $question = $this->checkQuestion($post['question_id']);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyAnswerLimit($user);

        $validator = new AnswerValidator();

        $validator->checkIfAllowAnswer($question, $user);

        $answer = new AnswerModel();

        $data = $this->handlePostData($post);

        $data['published'] = $this->getPublishStatus($user);
        $data['question_id'] = $question->id;
        $data['owner_id'] = $user->id;

        $answer->create($data);

        if ($answer->published == AnswerModel::PUBLISH_APPROVED) {

            $question->last_answer_id = $answer->id;
            $question->last_replier_id = $answer->owner_id;
            $question->last_reply_time = $answer->create_time;

            $question->update();

            if ($answer->owner_id != $question->owner_id) {
                $this->handleAnswerPostPoint($answer);
                $this->handleQuestionAnsweredNotice($answer);
            }
        }

        $this->saveDynamicAttrs($answer);
        $this->incrUserDailyAnswerCount($user);
        $this->recountQuestionAnswers($question);
        $this->recountUserAnswers($user);

        $this->eventsManager->fire('Answer:afterCreate', $this, $answer);

        return $answer;
    }

    protected function incrUserDailyAnswerCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrAnswerCount', $this, $user);
    }

    protected function recountQuestionAnswers(QuestionModel $question)
    {
        $questionRepo = new QuestionRepo();

        $answerCount = $questionRepo->countAnswers($question->id);

        $question->answer_count = $answerCount;

        $question->update();
    }

    protected function recountUserAnswers(UserModel $user)
    {
        $userRepo = new UserRepo();

        $answerCount = $userRepo->countAnswers($user->id);

        $user->answer_count = $answerCount;

        $user->update();
    }

    protected function handleQuestionAnsweredNotice(AnswerModel $answer)
    {
        $notice = new QuestionAnsweredNotice();

        $notice->handle($answer);
    }

    protected function handleAnswerPostPoint(AnswerModel $answer)
    {
        $service = new AnswerPostPointHistory();

        $service->handle($answer);
    }

}
