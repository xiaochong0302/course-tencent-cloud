<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Question as QuestionRepo;
use App\Repos\User as UserRepo;

class AnswerList extends Builder
{

    public function handleQuestions(array $answers)
    {
        $questions = $this->getQuestions($answers);

        foreach ($answers as $key => $answer) {
            $answers[$key]['question'] = $questions[$answer['question_id']] ?? new \stdClass();
        }

        return $answers;
    }

    public function handleUsers(array $answers)
    {
        $users = $this->getUsers($answers);

        foreach ($answers as $key => $answer) {
            $answers[$key]['owner'] = $users[$answer['owner_id']] ?? new \stdClass();
        }

        return $answers;
    }

    public function getQuestions(array $answers)
    {
        $ids = kg_array_column($answers, 'question_id');

        $questionRepo = new QuestionRepo();

        $questions = $questionRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($questions->toArray() as $question) {
            $result[$question['id']] = $question;
        }

        return $result;
    }

    public function getUsers(array $answers)
    {
        $ids = kg_array_column($answers, 'owner_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
