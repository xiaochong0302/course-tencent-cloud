<?php

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\QuestionFavorite as QuestionFavoriteModel;
use App\Models\User as UserModel;
use App\Repos\QuestionFavorite as QuestionFavoriteRepo;
use App\Services\Logic\Notice\System\QuestionFavorited as QuestionFavoritedNotice;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class QuestionFavorite extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkFavoriteLimit($user);

        $favoriteRepo = new QuestionFavoriteRepo();

        $favorite = $favoriteRepo->findQuestionFavorite($question->id, $user->id);

        if (!$favorite) {

            $action = 'do';

            $favorite = new QuestionFavoriteModel();

            $favorite->question_id = $question->id;
            $favorite->user_id = $user->id;

            $favorite->create();

            $this->incrQuestionFavoriteCount($question);

            $this->incrUserFavoriteCount($user);

            $this->handleFavoriteNotice($question, $user);

            $this->eventsManager->fire('Question:afterFavorite', $this, $question);

        } else {

            $action = 'undo';

            $favorite->delete();

            $this->decrQuestionFavoriteCount($question);

            $this->decrUserFavoriteCount($user);

            $this->eventsManager->fire('Question:afterUndoFavorite', $this, $question);
        }

        return [
            'action' => $action,
            'count' => $question->favorite_count,
        ];
    }

    protected function incrQuestionFavoriteCount(QuestionModel $question)
    {
        $question->favorite_count += 1;

        $question->update();
    }

    protected function decrQuestionFavoriteCount(QuestionModel $question)
    {
        if ($question->favorite_count > 0) {
            $question->favorite_count -= 1;
            $question->update();
        }
    }

    protected function incrUserFavoriteCount(UserModel $user)
    {
        $user->favorite_count += 1;

        $user->update();
    }

    protected function decrUserFavoriteCount(UserModel $user)
    {
        if ($user->favorite_count > 0) {
            $user->favorite_count -= 1;
            $user->update();
        }
    }

    protected function handleFavoriteNotice(QuestionModel $question, UserModel $sender)
    {
        $notice = new QuestionFavoritedNotice();

        $notice->handle($question, $sender);
    }

}
