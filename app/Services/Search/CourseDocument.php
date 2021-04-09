<?php

namespace App\Services\Search;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\User\Component;

class CourseDocument extends Component
{

    /**
     * 设置文档
     *
     * @param CourseModel $course
     * @return \XSDocument
     */
    public function setDocument(CourseModel $course)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($course);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param CourseModel $course
     * @return array
     */
    public function formatDocument(CourseModel $course)
    {
        if (is_array($course->attrs) || is_object($course->attrs)) {
            $course->attrs = kg_json_encode($course->attrs);
        }

        $teacher = '';

        if ($course->teacher_id > 0) {
            $record = UserModel::findFirst($course->teacher_id);
            $teacher = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $category = '';

        if ($course->category_id > 0) {
            $record = CategoryModel::findFirst($course->category_id);
            $category = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $course->cover = CourseModel::getCoverPath($course->cover);

        if (empty($article->summary)) {
            $course->summary = kg_parse_summary($course->details);
        }

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'category_id' => $course->category_id,
            'teacher_id' => $course->teacher_id,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'rating' => $course->rating,
            'score' => $course->score,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'category' => $category,
            'teacher' => $teacher,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

}
