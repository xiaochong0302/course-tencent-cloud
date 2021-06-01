<?php

namespace App\Services\Logic\Question;

use App\Builders\QuestionList as QuestionListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Question as QuestionModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Logic\Service as LogicService;
use App\Validators\QuestionQuery as QuestionQueryValidator;

class QuestionList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->checkQueryParams($params);

        $params['published'] = QuestionModel::PUBLISH_APPROVED;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $questionRepo = new QuestionRepo();

        $pager = $questionRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($pager);
    }

    public function handleQuestions($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new QuestionListBuilder();

        $questions = $pager->items->toArray();

        $users = $builder->getUsers($questions);

        $items = [];

        foreach ($questions as $question) {

            $question['tags'] = json_decode($question['tags'], true);

            $owner = $users[$question['owner_id']] ?? new \stdClass();

            $lastReplier = $users[$question['last_replier_id']] ?? new \stdClass();

            $items[] = [
                'id' => $question['id'],
                'title' => $question['title'],
                'cover' => $question['cover'],
                'summary' => $question['summary'],
                'tags' => $question['tags'],
                'bounty' => $question['bounty'],
                'anonymous' => $question['anonymous'],
                'closed' => $question['closed'],
                'solved' => $question['solved'],
                'published' => $question['published'],
                'view_count' => $question['view_count'],
                'like_count' => $question['like_count'],
                'answer_count' => $question['answer_count'],
                'comment_count' => $question['comment_count'],
                'favorite_count' => $question['favorite_count'],
                'last_reply_time' => $question['last_reply_time'],
                'create_time' => $question['create_time'],
                'update_time' => $question['update_time'],
                'last_replier' => $lastReplier,
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function checkQueryParams($params)
    {
        $validator = new QuestionQueryValidator();

        $query = [];

        if (isset($params['tag_id'])) {
            $query['tag_id'] = $validator->checkTag($params['tag_id']);
        }

        if (isset($params['sort'])) {
            $query['sort'] = $validator->checkSort($params['sort']);
        }

        return $query;
    }

}
