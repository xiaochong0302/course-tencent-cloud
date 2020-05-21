<?php

namespace App\Http\Web\Services;

use App\Caches\Category as CategoryCache;
use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;

class CourseQuery extends Service
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'web.course.list']);
    }

    public function handleTopCategories()
    {
        $params = $this->getQueryParams();

        if (isset($params['tc'])) {
            unset($params['tc']);
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
        }

        $baseUrl = $this->url->get(['for' => 'web.course.list']);

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $baseUrl . $this->buildQueryParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $categoryService = new CategoryService();

        $topCategories = $categoryService->getChildCategories(0);

        foreach ($topCategories as $key => $category) {
            $params['tc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleSubCategories()
    {
        $params = $this->getQueryParams();

        if (empty($params['tc'])) {
            return [];
        }

        $categoryService = new CategoryService();

        $subCategories = $categoryService->getChildCategories($params['tc']);

        if (empty($subCategories)) {
            return [];
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
        }

        $baseUrl = $this->url->get(['for' => 'web.course.list']);

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $baseUrl . $this->buildQueryParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        foreach ($subCategories as $key => $category) {
            $params['sc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleModels()
    {
        $params = $this->getQueryParams();

        if (isset($params['model'])) {
            unset($params['model']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $this->baseUrl . $this->buildQueryParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $models = CourseModel::modelTypes();

        foreach ($models as $key => $value) {
            $params['model'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleLevels()
    {
        $params = $this->getQueryParams();

        if (isset($params['level'])) {
            unset($params['level']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $this->baseUrl . $this->buildQueryParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $levels = CourseModel::levelTypes();

        foreach ($levels as $key => $value) {
            $params['level'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleSorts()
    {
        $params = $this->getQueryParams();

        $result = [];

        $sorts = CourseModel::sortTypes();

        foreach ($sorts as $key => $value) {
            $params['sort'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleCategoryPaths($categoryId)
    {
        $result = [];

        $cache = new CategoryCache();

        $subCategory = $cache->get($categoryId);
        $topCategory = $cache->get($subCategory->parent_id);

        $topParams = ['tc' => $topCategory->id];

        $result['top'] = [
            'name' => $topCategory->name,
            'url' => $this->baseUrl . $this->buildQueryParams($topParams),
        ];

        $subParams = ['tc' => $topCategory->id, 'sc' => $subCategory->id];

        $result['sub'] = [
            'name' => $subCategory->name,
            'url' => $this->baseUrl . $this->buildQueryParams($subParams),
        ];

        return $result;
    }

    protected function getQueryParams()
    {
        $query = $this->request->getQuery();

        $params = [];

        $validator = new \App\Validators\CourseQuery();

        if (isset($query['tc']) && $query['tc'] != 'all') {
            $validator->checkTopCategory($query['tc']);
            $params['tc'] = $query['tc'];
        }

        if (isset($query['sc']) && $query['tc'] != 'all') {
            $validator->checkSubCategory($query['sc']);
            $params['sc'] = $query['sc'];
        }

        if (isset($query['model']) && $query['model'] != 'all') {
            $validator->checkModel($query['model']);
            $params['model'] = $query['model'];
        }

        if (isset($query['level']) && $query['level'] != 'all') {
            $validator->checkLevel($query['level']);
            $params['level'] = $query['level'];
        }

        if (isset($query['sort'])) {
            $validator->checkSort($query['sort']);
            $params['sort'] = $query['sort'];
        }

        return $params;
    }

    protected function buildQueryParams($params)
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
