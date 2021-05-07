<?php

namespace App\Services\Logic\Question;

use App\Caches\Category as CategoryCache;
use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\QuestionFavorite as QuestionFavoriteRepo;
use App\Repos\QuestionLike as QuestionLikeRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;

class QuestionInfo extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $user = $this->getCurrentUser(true);

        $question = $this->checkQuestion($id);

        $result = $this->handleQuestion($question, $user);

        $this->incrQuestionViewCount($question);

        $this->eventsManager->fire('Question:afterView', $this, $question);

        return $result;
    }

    protected function handleQuestion(QuestionModel $question, UserModel $user)
    {
        $content = kg_parse_markdown($question->content);

        $category = $this->handleCategoryInfo($question);
        $owner = $this->handleUserInfo($question->owner_id);
        $replier = $this->handleUserInfo($question->replier_id);
        $me = $this->handleMeInfo($question, $user);

        return [
            'id' => $question->id,
            'title' => $question->title,
            'summary' => $question->summary,
            'content' => $content,
            'tags' => $question->tags,
            'category' => $category,
            'owner' => $owner,
            'replier' => $replier,
            'me' => $me,
            'bounty' => $question->bounty,
            'anonymous' => $question->anonymous,
            'solved' => $question->solved,
            'closed' => $question->closed,
            'view_count' => $question->view_count,
            'like_count' => $question->like_count,
            'answer_count' => $question->answer_count,
            'comment_count' => $question->comment_count,
            'favorite_count' => $question->favorite_count,
            'reply_time' => $question->reply_time,
            'create_time' => $question->create_time,
            'update_time' => $question->update_time,
        ];
    }

    protected function handleCategoryInfo(QuestionModel $question)
    {
        $cache = new CategoryCache();

        /**
         * @var CategoryModel $category
         */
        $category = $cache->get($question->category_id);

        if (!$category) return new \stdClass();

        return [
            'id' => $category->id,
            'name' => $category->name,
        ];
    }

    protected function handleMeInfo(QuestionModel $question, UserModel $user)
    {
        $me = [
            'liked' => 0,
            'favorited' => 0,
            'answered' => 0,
        ];

        if ($user->id > 0) {

            $likeRepo = new QuestionLikeRepo();

            $like = $likeRepo->findQuestionLike($question->id, $user->id);

            if ($like) {
                $me['liked'] = 1;
            }

            $favoriteRepo = new QuestionFavoriteRepo();

            $favorite = $favoriteRepo->findQuestionFavorite($question->id, $user->id);

            if ($favorite) {
                $me['favorited'] = 1;
            }

            $questionRepo = new QuestionRepo();

            $userAnswers = $questionRepo->findUserAnswers($question->id, $user->id);

            if ($userAnswers->count() > 0) {
                $me['answered'] = 1;
            }
        }

        return $me;
    }

    protected function handleUserInfo($userId)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($userId);

        if (!$user) return new \stdClass();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'vip' => $user->vip,
        ];
    }

    protected function incrQuestionViewCount(QuestionModel $question)
    {
        $question->view_count += 1;

        $question->update();
    }

}
