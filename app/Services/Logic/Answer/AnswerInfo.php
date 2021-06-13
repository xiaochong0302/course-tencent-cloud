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
use App\Repos\User as UserRepo;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Service as LogicService;

class AnswerInfo extends LogicService
{

    use AnswerTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $user = $this->getCurrentUser();

        return $this->handleAnswer($answer, $user);
    }

    protected function handleAnswer(AnswerModel $answer, UserModel $user)
    {
        $answer->content = kg_parse_markdown($answer->content);

        $result = [
            'id' => $answer->id,
            'content' => $answer->content,
            'anonymous' => $answer->anonymous,
            'accepted' => $answer->accepted,
            'published' => $answer->published,
            'like_count' => $answer->like_count,
            'create_time' => $answer->create_time,
            'update_time' => $answer->update_time,
        ];

        $result['question'] = $this->handleQuestionInfo($answer);
        $result['owner'] = $this->handleOwnerInfo($answer);
        $result['me'] = $this->handleMeInfo($answer, $user);

        return $result;
    }

    protected function handleQuestionInfo(AnswerModel $answer)
    {
        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($answer->question_id);

        return [
            'id' => $question->id,
            'title' => $question->title,
        ];
    }

    protected function handleOwnerInfo(AnswerModel $answer)
    {
        $userRepo = new UserRepo();

        $owner = $userRepo->findById($answer->owner_id);

        return [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];
    }

    protected function handleMeInfo(AnswerModel $answer, UserModel $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        $isOwner = $user->id == $answer->owner_id;
        $approved = $answer->published = AnswerModel::PUBLISH_APPROVED;

        if ($isOwner || $approved) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new AnswerLikeRepo();

            $like = $likeRepo->findAnswerLike($answer->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
