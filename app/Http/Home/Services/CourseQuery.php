<?php

namespace App\Http\Home\Services;

use App\Caches\Category as CategoryCache;
use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;
use App\Validators\CourseQuery as CourseQueryValidator;

class CourseQuery extends Service
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'home.course.list']);
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

        $baseUrl = $this->url->get(['for' => 'home.course.list']);

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $categoryService = new CategoryService();

        $topCategories = $categoryService->getChildCategories(CategoryModel::TYPE_COURSE, 0);

        foreach ($topCategories as $key => $category) {
            $params['tc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $baseUrl . $this->buildParams($params),
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

        $subCategories = $categoryService->getChildCategories(CategoryModel::TYPE_COURSE, $params['tc']);

        if (empty($subCategories)) {
            return [];
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
        }

        $baseUrl = $this->url->get(['for' => 'home.course.list']);

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        foreach ($subCategories as $key => $category) {
            $params['sc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleModels()
    {
        $params = $this->getParams();

        if (isset($params['model'])) {
            unset($params['model']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $models = CourseModel::modelTypes();

        foreach ($models as $key => $value) {
            $params['model'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleLevels()
    {
        $params = $this->getParams();

        if (isset($params['level'])) {
            unset($params['level']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => '全部',
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $levels = CourseModel::levelTypes();

        foreach ($levels as $key => $value) {
            $params['level'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleSorts()
    {
        $params = $this->getParams();

        $result = [];

        $sorts = CourseModel::sortTypes();

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

    public function handleCategoryPaths($categoryId)
    {
        $result = [];

        $cache = new CategoryCache();

        $subCategory = $cache->get($categoryId);
        $topCategory = $cache->get($subCategory->parent_id);

        $topParams = ['tc' => $topCategory->id];

        $result['top'] = [
            'name' => $topCategory->name,
            'url' => $this->baseUrl . $this->buildParams($topParams),
        ];

        $subParams = ['tc' => $topCategory->id, 'sc' => $subCategory->id];

        $result['sub'] = [
            'name' => $subCategory->name,
            'url' => $this->baseUrl . $this->buildParams($subParams),
        ];

        return $result;
    }

    public function getParams()
    {
        $query = $this->request->getQuery();

        $params = [];

        $validator = new CourseQueryValidator();

        if (isset($query['tc']) && $query['tc'] != 'all') {
            $validator->checkTopCategory($query['tc']);
            $params['tc'] = $query['tc'];
        }

        if (isset($query['sc']) && $query['sc'] != 'all') {
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

    protected function buildParams($params)
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
