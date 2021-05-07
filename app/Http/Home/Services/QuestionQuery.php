<?php

namespace App\Http\Home\Services;

use App\Models\Question as QuestionModel;
use App\Validators\QuestionQuery as QuestionQueryValidator;

class QuestionQuery extends Service
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'home.question.list']);
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
