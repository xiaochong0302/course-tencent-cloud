<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Builders\ArticleList as ArticleListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Article as ArticleModel;
use App\Repos\Article as ArticleRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Service as LogicService;
use App\Validators\ArticleQuery as ArticleQueryValidator;
use Phalcon\Text;

class ArticleList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->checkQueryParams($params);

        /**
         * tc => top_category
         * sc => sub_category
         */
        if (!empty($params['sc'])) {

            $params['category_id'] = $params['sc'];

        } elseif (!empty($params['tc'])) {

            $categoryService = new CategoryService();

            $childCategoryIds = $categoryService->getChildCategoryIds($params['tc']);

            $parentCategoryIds = [$params['tc']];

            $allCategoryIds = array_merge($parentCategoryIds, $childCategoryIds);

            $params['category_id'] = $allCategoryIds;
        }

        $params['published'] = ArticleModel::PUBLISH_APPROVED;
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

        $cosUrl = kg_cos_url();

        foreach ($articles as $article) {

            if (!empty($article['cover']) && !Text::startsWith($article['cover'], 'http')) {
                $article['cover'] = $cosUrl . $article['cover'];
            }

            $article['tags'] = json_decode($article['tags'], true);

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
                'published' => $article['published'],
                'closed' => $article['closed'],
                'view_count' => $article['view_count'],
                'like_count' => $article['like_count'],
                'comment_count' => $article['comment_count'],
                'favorite_count' => $article['favorite_count'],
                'create_time' => $article['create_time'],
                'update_time' => $article['update_time'],
                'category' => $category,
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function checkQueryParams($params)
    {
        $validator = new ArticleQueryValidator();

        $query = [];

        if (isset($params['owner_id'])) {
            $user = $validator->checkUser($params['owner_id']);
            $query['owner_id'] = $user->id;
        }

        if (isset($params['tag_id'])) {
            $tag = $validator->checkTag($params['tag_id']);
            $query['tag_id'] = $tag->id;
        }

        if (isset($params['tc'])) {
            $category = $validator->checkCategory($params['tc']);
            $query['tc'] = $category->id;
        }

        if (isset($params['sc'])) {
            $category = $validator->checkCategory($params['sc']);
            $query['sc'] = $category->id;
        }

        if (isset($params['sort'])) {
            $query['sort'] = $validator->checkSort($params['sort']);
        }

        return $query;
    }

}
