<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;
use App\Validators\CourseQuery as CourseQueryValidator;

class CourseQuery extends Service
{

    /**
     * @var string
     */
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'home.course.list']);
    }

    public function handleTopCategories(): array
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

        $topCategories = $categoryService->getChildCategories(CategoryModel::TYPE_COURSE, 0);

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

    public function handleSubCategories(): array
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

    public function handleModels(): array
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

    public function handleLevels(): array
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

    public function handleSorts(): array
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

    public function getParams(): array
    {
        $query = $this->request->getQuery();

        $params = [];

        $validator = new CourseQueryValidator();

        if (isset($query['tc']) && $query['tc'] != 'all') {
            $category = $validator->checkCategory((int)$query['tc']);
            $params['tc'] = $category->id;
        }

        if (isset($query['sc']) && $query['sc'] != 'all') {
            $category = $validator->checkCategory((int)$query['sc']);
            $params['sc'] = $category->id;
        }

        if (isset($query['model']) && $query['model'] != 'all') {
            $params['model'] = $validator->checkModel((int)$query['model']);
        }

        if (isset($query['level']) && $query['level'] != 'all') {
            $params['level'] = $validator->checkLevel((int)$query['level']);
        }

        if (isset($query['sort'])) {
            $params['sort'] = $validator->checkSort($query['sort']);
        }

        return $params;
    }

    protected function buildParams(array $params = []): string
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
