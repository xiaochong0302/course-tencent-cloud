<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use App\Models\Course as CourseModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\User as UserRepo;
use Phalcon\Di\Injectable;
use XSDocument;

class CourseDocument extends Injectable
{

    /**
     * 设置文档
     *
     * @param CourseModel $course
     * @return XSDocument
     */
    public function setDocument(CourseModel $course): XSDocument
    {
        $doc = new XSDocument();

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
    public function formatDocument(CourseModel $course): array
    {
        $tags = '[]';

        if (is_array($course->tags)) {
            $tags = kg_json_encode($course->tags);
        }

        $attrs = '{}';

        if (is_array($course->attrs)) {
            $attrs = kg_json_encode($course->attrs);
        }

        $category = '{}';

        if ($course->category_id > 0) {
            $category = $this->handleCategory($course->category_id);
        }

        $teacher = '{}';

        if ($course->teacher_id > 0) {
            $teacher = $this->handleUser($course->teacher_id);
        }

        $userCount = $course->user_count;

        if ($course->fake_user_count > $course->user_count) {
            $userCount = $course->fake_user_count;
        }

        $cover = CourseModel::getCoverPath($course->cover);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $cover,
            'summary' => $course->summary,
            'keywords' => $course->keywords,
            'tags' => $tags,
            'attrs' => $attrs,
            'rating' => $course->rating,
            'model' => $course->model,
            'level' => $course->level,
            'category_id' => $course->category_id,
            'teacher_id' => $course->teacher_id,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'user_count' => $userCount,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
            'create_time' => $course->create_time,
            'category' => $category,
            'teacher' => $teacher,
        ];
    }

    protected function handleCategory(int $id): string
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        return kg_json_encode([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

    protected function handleUser(int $id): string
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        return kg_json_encode([
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }

}
