<?php

namespace App\Builders;

use App\Repos\Article as ArticleRepo;
use App\Repos\User as UserRepo;
use Phalcon\Text;

class ArticleFavoriteList extends Builder
{

    public function handleArticles(array $relations)
    {
        $articles = $this->getArticles($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['article'] = $articles[$value['article_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? new \stdClass();
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

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
