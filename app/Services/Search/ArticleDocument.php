<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use App\Models\Article as ArticleModel;
use App\Models\User as UserModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\User as UserRepo;
use Phalcon\Di\Injectable;

class ArticleDocument extends Injectable
{

    /**
     * 设置文档
     *
     * @param ArticleModel $article
     * @return \XSDocument
     */
    public function setDocument(ArticleModel $article)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($article);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param ArticleModel $article
     * @return array
     */
    public function formatDocument(ArticleModel $article)
    {
        if (is_array($article->tags)) {
            $article->tags = kg_json_encode($article->tags);
        }

        $owner = '{}';

        if ($article->owner_id > 0) {
            $owner = $this->handleUser($article->owner_id);
        }

        $category = '{}';

        if ($article->category_id > 0) {
            $category = $this->handleCategory($article->category_id);
        }

        $article->cover = ArticleModel::getCoverPath($article->cover);

        return [
            'id' => $article->id,
            'title' => $article->title,
            'cover' => $article->cover,
            'summary' => $article->summary,
            'tags' => $article->tags,
            'category_id' => $article->category_id,
            'owner_id' => $article->owner_id,
            'create_time' => $article->create_time,
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'comment_count' => $article->comment_count,
            'favorite_count' => $article->favorite_count,
            'category' => $category,
            'owner' => $owner,
        ];
    }

    protected function handleUser($id)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        return kg_json_encode([
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }

    protected function handleCategory($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        return kg_json_encode([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

}
