<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Services\Category as CategoryService;
use App\Validators\QuestionQuery as QuestionQueryValidator;

class QuestionQuery extends Service
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'home.question.list']);
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

        $topCategories = $categoryService->getChildCategories(CategoryModel::TYPE_QUESTION, 0);

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

        $sorts = QuestionModel::sortTypes();

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

        $validator = new QuestionQueryValidator();

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
