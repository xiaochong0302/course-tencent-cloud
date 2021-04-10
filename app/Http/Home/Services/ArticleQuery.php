<?php

namespace App\Http\Home\Services;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Services\Category as CategoryService;
use App\Validators\ArticleQuery as ArticleQueryValidator;

class ArticleQuery extends Service
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'home.article.list']);
    }

    public function handleCategories()
    {
        $params = $this->getParams();

        if (isset($params['category_id'])) {
            unset($params['category_id']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $categoryService = new CategoryService();

        $topCategories = $categoryService->getChildCategories(CategoryModel::TYPE_ARTICLE, 0);

        foreach ($topCategories as $key => $category) {
            $params['category_id'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleSorts()
    {
        $params = $this->getParams();

        $result = [];

        $sorts = ArticleModel::sortTypes();

        foreach ($sorts as $key => $value) {
            $params['sort'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function getParams()
    {
        $query = $this->request->getQuery();

        $params = [];

        $validator = new ArticleQueryValidator();

        if (isset($query['category_id']) && $query['category_id'] != 'all') {
            $validator->checkCategory($query['category_id']);
            $params['category_id'] = $query['category_id'];
        }

        if (isset($query['tag_id'])) {
            $validator->checkTag($query['tag_id']);
            $params['tag_id'] = $query['tag_id'];
        }

        if (isset($query['sort'])) {
            $validator->checkSort($query['sort']);
            $params['sort'] = $query['sort'];
        }

        return $params;
    }

    protected function buildParams($params)
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
