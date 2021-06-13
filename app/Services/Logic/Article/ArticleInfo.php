<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Caches\Category as CategoryCache;
use App\Caches\User as UserCache;
use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\User as UserModel;
use App\Repos\ArticleFavorite as ArticleFavoriteRepo;
use App\Repos\ArticleLike as ArticleLikeRepo;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;

class ArticleInfo extends LogicService
{

    use ArticleTrait;

    public function handle($id)
    {
        $user = $this->getCurrentUser(true);

        $article = $this->checkArticle($id);

        $result = $this->handleArticle($article, $user);

        $this->incrArticleViewCount($article);

        $this->eventsManager->fire('Article:afterView', $this, $article);

        return $result;
    }

    protected function handleArticle(ArticleModel $article, UserModel $user)
    {
        $content = kg_parse_markdown($article->content);

        $category = $this->handleCategoryInfo($article);
        $owner = $this->handleOwnerInfo($article);
        $me = $this->handleMeInfo($article, $user);

        return [
            'id' => $article->id,
            'title' => $article->title,
            'cover' => $article->cover,
            'summary' => $article->summary,
            'tags' => $article->tags,
            'content' => $content,
            'private' => $article->private,
            'closed' => $article->closed,
            'published' => $article->published,
            'source_type' => $article->source_type,
            'source_url' => $article->source_url,
            'word_count' => $article->word_count,
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'comment_count' => $article->comment_count,
            'favorite_count' => $article->favorite_count,
            'create_time' => $article->create_time,
            'update_time' => $article->update_time,
            'category' => $category,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleMeInfo(ArticleModel $article, UserModel $user)
    {
        $me = [
            'liked' => 0,
            'favorited' => 0,
            'owned' => 0,
        ];

        $isOwner = $user->id == $article->owner_id;
        $approved = $article->published == ArticleModel::PUBLISH_APPROVED;
        $public = $article->private == 0;

        if ($approved && $public) {
            $me['owned'] = 1;
        } else {
            $me['owned'] = $isOwner ? 1 : 0;
        }

        if ($user->id > 0) {

            $likeRepo = new ArticleLikeRepo();

            $like = $likeRepo->findArticleLike($article->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            $favoriteRepo = new ArticleFavoriteRepo();

            $favorite = $favoriteRepo->findArticleFavorite($article->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }
        }

        return $me;
    }

    protected function handleCategoryInfo(ArticleModel $article)
    {
        $cache = new CategoryCache();

        /**
         * @var CategoryModel $category
         */
        $category = $cache->get($article->category_id);

        if (!$category) return new \stdClass();

        return [
            'id' => $category->id,
            'name' => $category->name,
        ];
    }

    protected function handleOwnerInfo(ArticleModel $article)
    {
        $cache = new UserCache();

        /**
         * @var UserModel $owner
         */
        $owner = $cache->get($article->owner_id);

        if (!$owner) return new \stdClass();

        return [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
            'title' => $owner->title,
            'about' => $owner->about,
            'vip' => $owner->vip,
        ];
    }

    protected function incrArticleViewCount(ArticleModel $article)
    {
        $article->view_count += 1;

        $article->update();
    }

}
