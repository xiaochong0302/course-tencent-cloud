<?php

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;

class QuestionCreate extends LogicService
{

    use QuestionDataTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $question = new QuestionModel();

        $data = $this->handlePostData($post);

        $data['owner_id'] = $user->id;

        $question->create($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($question, $post['xm_tag_ids']);
        }

        $this->incrUserQuestionCount($user);

        $this->eventsManager->fire('Question:afterCreate', $this, $question);

        return $question;
    }

    protected function incrUserQuestionCount(UserModel $user)
    {
        $user->question_count += 1;

        $user->update();
    }

}
