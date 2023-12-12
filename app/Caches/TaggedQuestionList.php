<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Question as QuestionModel;
use App\Repos\Question as QuestionRepo;

class TaggedQuestionList extends Cache
{

    protected $limit = 15;

    protected $lifetime = 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "tagged_question_list:{$id}";
    }

    public function getContent($id = null)
    {

        $questionRepo = new QuestionRepo();

        $where = [
            'tag_id' => $id,
            'published' => QuestionModel::PUBLISH_APPROVED,
            'deleted' => 0,
        ];

        $pager = $questionRepo->paginate($where);

        if ($pager->total_items == 0) return [];

        return $this->handleContent($pager->items);
    }

    /**
     * @param QuestionModel[] $questions
     * @return array
     */
    public function handleContent($questions)
    {
        $result = [];

        $count = 0;

        foreach ($questions as $question) {
            if ($count < $this->limit) {
                $result[] = [
                    'id' => $question->id,
                    'title' => $question->title,
                    'view_count' => $question->view_count,
                    'like_count' => $question->like_count,
                    'answer_count' => $question->answer_count,
                    'favorite_count' => $question->favorite_count,
                ];
                $count++;
            }
        }

        return $result;
    }

}
