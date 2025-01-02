<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Question as QuestionRepo;
use Phalcon\Text;

class QuestionFavoriteList extends Builder
{

    public function handleQuestions(array $relations)
    {
        $questions = $this->getQuestions($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['question'] = $questions[$value['question_id']] ?? null;
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? null;
        }

        return $relations;
    }

    public function getQuestions(array $relations)
    {
        $ids = kg_array_column($relations, 'question_id');

        $questionRepo = new QuestionRepo();

        $columns = [
            'id', 'title', 'cover',
            'view_count', 'like_count',
            'answer_count', 'favorite_count',
        ];

        $questions = $questionRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($questions->toArray() as $question) {

            if (!empty($question['cover']) && !Text::startsWith($question['cover'], 'http')) {
                $question['cover'] = $baseUrl . $question['cover'];
            }

            $result[$question['id']] = $question;
        }

        return $result;
    }

    public function getUsers(array $relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        return $this->getShallowUserByIds($ids);
    }

}
