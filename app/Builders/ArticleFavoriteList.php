<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Article as ArticleRepo;
use Phalcon\Text;

class ArticleFavoriteList extends Builder
{

    public function handleArticles(array $relations)
    {
        $articles = $this->getArticles($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['article'] = $articles[$value['article_id']] ?? null;
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? null;
        }

        return $relations;
    }

    public function getArticles(array $relations)
    {
        $ids = kg_array_column($relations, 'article_id');

        $articleRepo = new ArticleRepo();

        $columns = [
            'id', 'title', 'cover',
            'view_count', 'like_count',
            'comment_count', 'favorite_count',
        ];

        $articles = $articleRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($articles->toArray() as $article) {

            if (!empty($article['cover']) && !Text::startsWith($article['cover'], 'http')) {
                $article['cover'] = $baseUrl . $article['cover'];
            }

            $result[$article['id']] = $article;
        }

        return $result;
    }

    public function getUsers(array $relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        return $this->getShallowUserByIds($ids);
    }

}
