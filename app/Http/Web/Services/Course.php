<?php

namespace App\Http\Web\Services;

use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;

class Course extends Service
{

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
            'id' => 0,
            'name' => '全部',
            'href' => $baseUrl . $this->buildQueryParams($params),
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
                'href' => $baseUrl . $this->buildQueryParams($params),
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
            'id' => 0,
            'name' => '全部',
            'href' => $baseUrl . $this->buildQueryParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        foreach ($subCategories as $key => $category) {
            $params['sc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'href' => $baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleLevels()
    {
        $params = $this->getQueryParams();

        $defaultParams = $params;

        if (isset($defaultParams['level'])) {
            unset($defaultParams['level']);
        }

        $baseUrl = $this->url->get(['for' => 'web.course.list']);

        $defaultItem = [
            'id' => 0,
            'name' => '全部',
            'href' => $baseUrl . $this->buildQueryParams($defaultParams),
        ];

        $result = [];

        $result[] = $defaultItem;

        $levels = CourseModel::levelTypes();

        foreach ($levels as $key => $value) {
            $params['sc'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'href' => $baseUrl . $this->buildQueryParams($params),
            ];
        }

        return $result;
    }

    public function handleSorts()
    {
    }

    protected function getQueryParams()
    {
        $query = $this->request->getQuery();

        $params = [];

        if (!empty($query['tc'])) {
            $params['tc'] = $query['tc'];
        }

        if (!empty($query['sc'])) {
            $params['sc'] = $query['sc'];
        }

        if (!empty($query['level'])) {
            $params['level'] = $query['level'];
        }

        return $params;
    }

    protected function buildQueryParams($params)
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
