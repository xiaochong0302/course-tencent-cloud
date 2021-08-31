<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\User as UserRepo;
use Phalcon\Di\Injectable;

class CourseDocument extends Injectable
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

        if (is_array($course->tags) || is_object($course->tags)) {
            $course->tags = kg_json_encode($course->tags);
        }

        $teacher = '{}';

        if ($course->teacher_id > 0) {
            $teacher = $this->handleUser($course->teacher_id);
        }

        $category = '{}';

        if ($course->category_id > 0) {
            $category = $this->handleCategory($course->category_id);
        }

        $course->cover = CourseModel::getCoverPath($course->cover);

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
            'tags' => $course->tags,
            'category' => $category,
            'teacher' => $teacher,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

    protected function handleUser($id)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        $user->avatar = UserModel::getAvatarPath($user->avatar);

        return kg_json_encode([
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ]);
    }

    protected function handleCategory($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        return kg_json_encode([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

}
