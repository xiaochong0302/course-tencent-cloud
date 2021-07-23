<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        $isFirstTime = true;

        if (!$favorite) {

            $favorite = new QuestionFavoriteModel();

            $favorite->question_id = $question->id;
            $favorite->user_id = $user->id;

            $favorite->create();

        } else {

            $isFirstTime = false;

            $favorite->deleted = $favorite->deleted == 1 ? 0 : 1;

            $favorite->update();
        }

        if ($favorite->deleted == 0) {

            $action = 'do';

            $this->incrQuestionFavoriteCount($question);
            $this->incrUserFavoriteCount($user);

            $this->eventsManager->fire('Question:afterFavorite', $this, $question);

        } else {

            $action = 'undo';

            $this->decrQuestionFavoriteCount($question);
            $this->decrUserFavoriteCount($user);

            $this->eventsManager->fire('Question:afterUndoFavorite', $this, $question);
        }

        $isOwner = $user->id == $question->owner_id;

        /**
         * 仅首次收藏发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleFavoriteNotice($question, $user);
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
