<?php

namespace App\Services\Search;

use App\Models\Answer as AnswerModel;
use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\User\Component;

class QuestionDocument extends Component
{

    /**
     * 设置文档
     *
     * @param QuestionModel $question
     * @return \XSDocument
     */
    public function setDocument(QuestionModel $question)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($question);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param QuestionModel $question
     * @return array
     */
    public function formatDocument(QuestionModel $question)
    {
        if (empty($question->summary)) {
            $question->summary = kg_parse_summary($question->content);
        }

        if (is_array($question->tags) || is_object($question->tags)) {
            $question->tags = kg_json_encode($question->tags);
        }

        $category = '{}';

        if ($question->category_id > 0) {
            $record = CategoryModel::findFirst($question->category_id);
            $category = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $owner = '{}';

        if ($question->owner_id > 0) {
            $record = UserModel::findFirst($question->owner_id);
            $owner = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $lastReplier = '{}';

        if ($question->last_replier_id > 0) {
            $record = UserModel::findFirst($question->last_replier_id);
            $lastReplier = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $lastAnswer = '{}';

        if ($question->last_answer_id > 0) {
            $record = AnswerModel::findFirst($question->last_answer_id);
            $lastAnswer = kg_json_encode([
                'id' => $record->id,
                'summary' => kg_parse_summary($record->content),
            ]);
        }

        $acceptAnswer = '{}';

        if ($question->accept_answer_id > 0) {
            $record = AnswerModel::findFirst($question->accept_answer_id);
            $lastAnswer = kg_json_encode([
                'id' => $record->id,
                'summary' => kg_parse_summary($record->content),
            ]);
        }

        return [
            'id' => $question->id,
            'title' => $question->title,
            'cover' => $question->cover,
            'summary' => $question->summary,
            'category_id' => $question->category_id,
            'owner_id' => $question->owner_id,
            'create_time' => $question->create_time,
            'last_reply_time' => $question->last_reply_time,
            'tags' => $question->tags,
            'category' => $category,
            'owner' => $owner,
            'last_replier' => $lastReplier,
            'last_answer' => $lastAnswer,
            'accept_answer' => $acceptAnswer,
            'bounty' => $question->bounty,
            'anonymous' => $question->anonymous,
            'solved' => $question->solved,
            'view_count' => $question->view_count,
            'like_count' => $question->like_count,
            'answer_count' => $question->answer_count,
            'comment_count' => $question->comment_count,
            'favorite_count' => $question->favorite_count,
        ];
    }

}
