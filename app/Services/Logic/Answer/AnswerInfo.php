<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\User as UserModel;
use App\Repos\AnswerLike as AnswerLikeRepo;
use App\Repos\Question as QuestionRepo;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class AnswerInfo extends LogicService
{

    use AnswerTrait;
    use UserTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $user = $this->getCurrentUser();

        return $this->handleAnswer($answer, $user);
    }

    protected function handleAnswer(AnswerModel $answer, UserModel $user)
    {
        $question = $this->handleQuestionInfo($answer->question_id);
        $owner = $this->handleShallowUserInfo($answer->owner_id);
        $me = $this->handleMeInfo($answer, $user);

        return [
            'id' => $answer->id,
            'content' => $answer->content,
            'anonymous' => $answer->anonymous,
            'accepted' => $answer->accepted,
            'published' => $answer->published,
            'deleted' => $answer->deleted,
            'comment_count' => $answer->comment_count,
            'like_count' => $answer->like_count,
            'create_time' => $answer->create_time,
            'update_time' => $answer->update_time,
            'question' => $question,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleQuestionInfo($questionId)
    {
        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($questionId);

        return [
            'id' => $question->id,
            'title' => $question->title,
        ];
    }

    protected function handleMeInfo(AnswerModel $answer, UserModel $user)
    {
        $me = [
            'logged' => 0,
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $answer->owner_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $me['logged'] = 1;

            $likeRepo = new AnswerLikeRepo();

            $like = $likeRepo->findAnswerLike($answer->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
