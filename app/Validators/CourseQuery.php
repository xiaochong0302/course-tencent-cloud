<?php

namespace App\Validators;

use App\Models\Course as CourseModel;
use App\Repos\Category as CategoryRepo;

class CourseQuery extends Validator
{

    public function checkCategory($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        if (!$category) {
            return false;
        }

        return $category->id;
    }

    public function checkLevel($level)
    {
        $types = CourseModel::levelTypes();

        if (!isset($types[$level])) {
            return $level;
        }

        return false;
    }

    public function checkModel($model)
    {
        $types = CourseModel::levelTypes();

        if (!isset($types[$model])) {
            return $model;
        }

        return false;
    }

    public function checkSort($sort)
    {
        switch ($sort) {
            case 'rating':
                $orderBy = 'rating DESC';
                break;
            case 'score':
                $orderBy = 'score DESC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        return $orderBy;
    }

}
