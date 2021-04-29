<?php

namespace App\Services\Logic\Article;

use App\Builders\ArticleList as ArticleListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Article as ArticleModel;
use App\Repos\Article as ArticleRepo;
use App\Services\Logic\Service as LogicService;
use App\Validators\ArticleQuery as ArticleQueryValidator;

class ArticleList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->checkQueryParams($params);

        $params['published'] = ArticleModel::PUBLISH_APPROVED;
        $params['private'] = 0;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $articleRepo = new ArticleRepo();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleArticles($pager);
    }

    public function handleArticles($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ArticleListBuilder();

        $categories = $builder->getCategories();

        $articles = $pager->items->toArray();

        $users = $builder->getUsers($articles);

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($articles as $article) {

            $article['cover'] = $baseUrl . $article['cover'];

            if (empty($article['summary'])) {
                $article['summary'] = kg_parse_summary($article['content']);
            }

            $article['tags'] = json_decode($article['tags']);

            $category = $categories[$article['category_id']] ?? new \stdClass();

            $owner = $users[$article['owner_id']] ?? new \stdClass();

            $items[] = [
                'id' => $article['id'],
                'title' => $article['title'],
                'cover' => $article['cover'],
                'summary' => $article['summary'],
                'source_type' => $article['source_type'],
                'source_url' => $article['source_url'],
                'tags' => $article['tags'],
                'category' => $category,
                'owner' => $owner,
                'private' => $article['private'],
                'published' => $article['published'],
                'allow_comment' => $article['allow_comment'],
                'view_count' => $article['view_count'],
                'like_count' => $article['like_count'],
                'comment_count' => $article['comment_count'],
                'favorite_count' => $article['favorite_count'],
                'create_time' => $article['create_time'],
                'update_time' => $article['update_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function checkQueryParams($params)
    {
        $validator = new ArticleQueryValidator();

        $query = [];

        if (isset($params['category_id'])) {
            $query['category_id'] = $validator->checkCategory($params['category_id']);
        }

        if (isset($params['tag_id'])) {
            $query['tag_id'] = $validator->checkTag($params['tag_id']);
        }

        return $query;
    }

}
