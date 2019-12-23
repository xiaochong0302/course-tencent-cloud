<?php

namespace App\Searchers;

use App\Models\Course as CourseModel;
use Phalcon\Mvc\User\Component as UserComponent;

class Course extends UserComponent
{

    /**
     * @var \XS
     */
    protected $xs;

    public function __construct()
    {
        $fileName = config_path() . '/xs.course.ini';

        $this->xs = new \XS($fileName);
    }

    /**
     * 获取XS
     * @return \XS
     */
    public function getXS()
    {
        return $this->xs;
    }

    /**
     * 搜索课程
     *
     * @param string $query
     * @param integer $limit
     * @param integer $offset
     * @return \stdClass
     * @throws \XSException
     */
    public function search($query, $limit = 15, $offset = 0)
    {
        $search = $this->xs->getSearch();

        $docs = $search->setQuery($query)->setLimit($limit, $offset)->search();

        $total = $search->getLastCount();

        $fields = array_keys($this->xs->getAllFields());

        $items = [];

        foreach ($docs as $doc) {
            $item = new \stdClass();
            foreach ($fields as $field) {
                if (in_array($field, ['title', 'summary'])) {
                    $item->{$field} = $search->highlight($doc->{$field});
                } else {
                    $item->{$field} = $doc->{$field};
                }
            }
            $items[] = $item;
        }

        $result = new \stdClass();

        $result->total = $total;
        $result->items = $items;

        return $result;
    }

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
        $data = [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'expiry' => $course->expiry,
            'rating' => $course->rating,
            'score' => $course->score,
            'model' => $course->model,
            'level' => $course->level,
            'student_count' => $course->student_count,
            'lesson_count' => $course->lesson_count,
            'created_at' => $course->created_at,
        ];

        return $data;
    }

}
