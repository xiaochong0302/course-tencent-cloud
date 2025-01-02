<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\QuestionFavorite as QuestionFavoriteRepo;
use App\Repos\QuestionLike as QuestionLikeRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo;

class QuestionInfo extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $user = $this->getCurrentUser();

        $question = $this->checkQuestion($id);

        $result = $this->handleQuestion($question, $user);

        $this->incrQuestionViewCount($question);

        $this->eventsManager->fire('Question:afterView', $this, $question);

        return $result;
    }

    protected function handleQuestion(QuestionModel $question, UserModel $user)
    {
        $lastReplier = $this->handleLastReplierInfo($question->last_replier_id);
        $categoryPaths = $this->handleCategoryPaths($question->category_id);
        $owner = $this->handleOwnerInfo($question->owner_id);
        $me = $this->handleMeInfo($question, $user);

        return [
            'id' => $question->id,
            'title' => $question->title,
            'summary' => $question->summary,
            'content' => $question->content,
            'keywords' => $question->keywords,
            'tags' => $question->tags,
            'bounty' => $question->bounty,
            'anonymous' => $question->anonymous,
            'solved' => $question->solved,
            'closed' => $question->closed,
            'published' => $question->published,
            'deleted' => $question->deleted,
            'view_count' => $question->view_count,
            'like_count' => $question->like_count,
            'answer_count' => $question->answer_count,
            'comment_count' => $question->comment_count,
            'favorite_count' => $question->favorite_count,
            'last_reply_time' => $question->last_reply_time,
            'create_time' => $question->create_time,
            'update_time' => $question->update_time,
            'last_replier' => $lastReplier,
            'category_paths' => $categoryPaths,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCategoryPaths($categoryId)
    {
        if ($categoryId == 0) return null;

        $service = new CategoryService();

        return $service->getCategoryPaths($categoryId);
    }

    protected function handleLastReplierInfo($userId)
    {
        if ($userId == 0) return null;

        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleOwnerInfo($userId)
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleMeInfo(QuestionModel $question, UserModel $user)
    {
        $me = [
            'allow_answer' => 0,
            'logged' => 0,
            'liked' => 0,
            'favorited' => 0,
            'answered' => 0,
            'owned' => 0,
        ];

        $approved = $question->published == QuestionModel::PUBLISH_APPROVED;
        $closed = $question->closed == 1;
        $solved = $question->solved == 1;

        if ($user->id > 0) {

            $me['logged'] = 1;

            if ($user->id == $question->owner_id) {
                $me['owned'] = 1;
            }

            $likeRepo = new QuestionLikeRepo();

            $like = $likeRepo->findQuestionLike($question->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            $favoriteRepo = new QuestionFavoriteRepo();

            $favorite = $favoriteRepo->findQuestionFavorite($question->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            $questionRepo = new QuestionRepo();

            $userAnswers = $questionRepo->findUserAnswers($question->id, $user->id);

            if ($userAnswers->count() > 0) {
                $me['answered'] = 1;
            }

            $answered = $me['answered'] == 1;

            if ($approved && !$closed && !$solved && !$answered) {
                $me['allow_answer'] = 1;
            }
        }

        return $me;
    }

    protected function incrQuestionViewCount(QuestionModel $question)
    {
        $question->view_count += 1;

        $question->update();
    }

}
