<?php

namespace App\Searchers;

use App\Models\Course as CourseModel;

class CourseDocumenter extends \Phalcon\Mvc\User\Component
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

        $data = [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'expiry' => $course->expiry,
            'score' => $course->score,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'created_at' => $course->created_at,
        ];

        return $data;
    }

}
