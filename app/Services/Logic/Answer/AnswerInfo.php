<?php

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
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

        return $this->handleAnswer($answer);
    }

    protected function handleAnswer(AnswerModel $answer)
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

}
