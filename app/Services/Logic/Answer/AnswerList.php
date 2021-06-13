<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Builders\AnswerList as AnswerListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Answer as AnswerRepo;
use App\Services\Logic\Service as LogicService;

class AnswerList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $answerRepo = new AnswerRepo();

        $pager = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($pager);
    }

    public function handleAnswers($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new AnswerListBuilder();

        $answers = $pager->items->toArray();

        $questions = $builder->getQuestions($answers);

        $users = $builder->getUsers($answers);

        $items = [];

        foreach ($answers as $answer) {

            $question = $questions[$answer['question_id']] ?? new \stdClass();
            $owner = $users[$answer['owner_id']] ?? new \stdClass();

            $items[] = [
                'id' => $answer['id'],
                'summary' => $answer['summary'],
                'published' => $answer['published'],
                'accepted' => $answer['accepted'],
                'comment_count' => $answer['comment_count'],
                'like_count' => $answer['like_count'],
                'create_time' => $answer['create_time'],
                'update_time' => $answer['update_time'],
                'question' => $question,
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
