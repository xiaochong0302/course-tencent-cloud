<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

    public function handleTopCategories()
    {
        $params = $this->getParams();

        if (isset($params['tc'])) {
            unset($params['tc']);
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
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

        foreach ($topCategories as $category) {
            $params['tc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleSubCategories()
    {
        $params = $this->getParams();

        if (empty($params['tc'])) {
            return [];
        }

        $categoryService = new CategoryService();

        $subCategories = $categoryService->getChildCategories(CategoryModel::TYPE_ARTICLE, $params['tc']);

        if (empty($subCategories)) {
            return [];
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        foreach ($subCategories as $category) {
            $params['sc'] = $category['id'];
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

        if (isset($query['tag_id'])) {
            $tag = $validator->checkTag($query['tag_id']);
            $params['tag_id'] = $tag->id;
        }

        if (isset($query['tc']) && $query['tc'] != 'all') {
            $category = $validator->checkCategory($query['tc']);
            $params['tc'] = $category->id;
        }

        if (isset($query['sc']) && $query['sc'] != 'all') {
            $category = $validator->checkCategory($query['sc']);
            $params['sc'] = $category->id;
        }

        if (isset($query['sort'])) {
            $params['sort'] = $validator->checkSort($query['sort']);;
        }

        return $params;
    }

    protected function buildParams($params)
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
