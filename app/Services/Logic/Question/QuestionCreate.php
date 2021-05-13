<?php

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
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

        $data['published'] = $this->getPublishStatus($user);

        $data['owner_id'] = $user->id;

        $question->create($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($question, $post['xm_tag_ids']);
        }

        $this->recountUserQuestions($user);

        $this->eventsManager->fire('Question:afterCreate', $this, $question);

        return $question;
    }

    protected function getPublishStatus(UserModel $user)
    {
        return $user->question_count > 100 ? QuestionModel::PUBLISH_APPROVED : QuestionModel::PUBLISH_PENDING;
    }

    protected function recountUserQuestions(UserModel $user)
    {
        $userRepo = new UserRepo();

        $questionCount = $userRepo->countQuestions($user->id);

        $user->question_count = $questionCount;

        $user->update();
    }

}
