<?php

namespace App\Http\Home\Services;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Course as CourseRepo;
use App\Transformers\ChapterList as ChapterListTransformer;
use App\Validators\Course as CourseFilter;

class CourseAdmin extends Service
{

    public function getCourse($id)
    {
        $course = $this->findOrFail($id);

        return $course;
    }

    public function getTopCategories()
    {
        $categoryRepo = new CategoryRepo();

        $categories = $categoryRepo->find([
            'parent_id' => 0,
            'status' => CategoryModel::STATUS_NORMAL,
        ]);

        return $categories;
    }

    public function getChapters($id)
    {
        $course = $this->findOrFail($id);

        $courseRepo = new CourseRepo();

        $topChapters = $courseRepo->findTopChapters($id);

        switch ($course->model) {

            case CourseModel::MODEL_VIDEO:
                $subChapters = $courseRepo->findVideoChapters($id);
                break;

            case CourseModel::MODEL_LIVE:
                $subChapters = $courseRepo->findLiveChapters($id);
                break;

            case CourseModel::MODEL_ARTICLE:
                $subChapters = $courseRepo->findArticleChapters($id);
                break;
        }

        $result = $this->handleChapters($topChapters, $subChapters);

        return $result;
    }

    public function create()
    {
        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new CourseFilter();

        $data = [];

        $data['user_id'] = $user->id;
        $data['status'] = CourseModel::STATUS_DRAFT;
        $data['category_id'] = $filter->checkCategoryId($post['category_id']);
        $data['model'] = $filter->checkModel($post['model']);
        $data['level'] = $filter->checkLevel($post['level']);
        $data['title'] = $filter->checkTitle($post['title']);
        $data['cover'] = $filter->checkCover($post['cover']);
        $data['summary'] = $filter->checkSummary($post['summary']);
        $data['keywords'] = $filter->checkKeywords($post['keywords']);
        $data['price'] = $filter->checkPrice($post['price']);
        $data['expiry'] = $filter->checkExpiry($post['expiry']);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->create($data);

        return $course;
    }

    public function update($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new CourseFilter();

        $filter->checkOwner($user->id, $course->user_id);

        $data = [];

        if (isset($post['category_id'])) {
            $data['category_id'] = $filter->checkCategoryId($post['category_id']);
        }

        if (isset($post['level'])) {
            $data['level'] = $filter->checkLevel($post['level']);
        }

        if (isset($post['title'])) {
            $data['title'] = $filter->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $filter->checkCover($post['cover']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $filter->checkSummary($post['summary']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $filter->checkKeywords($post['keywords']);
        }

        if (isset($post['price'])) {
            $data['price'] = $filter->checkPrice($post['price']);
        }

        $course->update($data);

        return $course;
    }

    public function delete($id)
    {
        $course = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $filter = new CourseFilter();

        $filter->checkOwner($user->id, $course->user_id);

        if ($course->status == CourseModel::STATUS_DELETED) {
            return false;
        }

        $course->status = CourseModel::STATUS_DELETED;

        $course->update();
    }

    private function findOrFail($id)
    {
        $repo = new CourseRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleChapters($topChapters, $subChapters)
    {
        $chapters = array_merge($topChapters->toArray(), $subChapters->toArray());

        $builder = new ChapterListTransformer();

        $tree = $builder->handleTree($chapters);

        return $builder->arrayToObject($tree);
    }

}
