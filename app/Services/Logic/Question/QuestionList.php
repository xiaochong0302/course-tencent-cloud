<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Builders\QuestionList as QuestionListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Question as QuestionModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Service as LogicService;
use App\Validators\QuestionQuery as QuestionQueryValidator;
use Phalcon\Text;

class QuestionList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->checkQueryParams($params);

        /**
         * tc => top_category
         * sc => sub_category
         */
        if (!empty($params['sc'])) {

            $params['category_id'] = $params['sc'];

        } elseif (!empty($params['tc'])) {

            $categoryService = new CategoryService();

            $childCategoryIds = $categoryService->getChildCategoryIds($params['tc']);

            $parentCategoryIds = [$params['tc']];

            $allCategoryIds = array_merge($parentCategoryIds, $childCategoryIds);

            $params['category_id'] = $allCategoryIds;
        }

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

        $categories = $builder->getCategories();

        $questions = $pager->items->toArray();

        $users = $builder->getUsers($questions);

        $items = [];

        $cosUrl = kg_cos_url();

        foreach ($questions as $question) {

            if (!empty($question['cover']) && !Text::startsWith($question['cover'], 'http')) {
                $question['cover'] = $cosUrl . $question['cover'];
            }

            $question['tags'] = json_decode($question['tags'], true);

            $category = $categories[$question['category_id']] ?? new \stdClass();

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
                'category' => $category,
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

        if (isset($params['owner_id'])) {
            $user = $validator->checkUser($params['owner_id']);
            $query['owner_id'] = $user->id;
        }

        if (isset($params['tag_id'])) {
            $tag = $validator->checkTag($params['tag_id']);
            $query['tag_id'] = $tag->id;
        }

        if (isset($params['tc'])) {
            $category = $validator->checkCategory($params['tc']);
            $query['tc'] = $category->id;
        }

        if (isset($params['sc'])) {
            $category = $validator->checkCategory($params['sc']);
            $query['sc'] = $category->id;
        }

        if (isset($params['sort'])) {
            $query['sort'] = $validator->checkSort($params['sort']);
        }

        return $query;
    }

}
