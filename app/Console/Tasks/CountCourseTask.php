<?php

namespace App\Console\Tasks;

use App\Models\Category as CategoryModel;
use App\Repos\Category as CategoryRepo;
use Phalcon\Cli\Task;

class CountCourseTask extends Task
{

    public function mainAction()
    {
        $categoryRepo = new CategoryRepo();

        $mapping = [];

        /**
         * @var CategoryModel[] $subCategories
         */
        $subCategories = $categoryRepo->findAll(['level' => 2, 'deleted' => 0]);

        foreach ($subCategories as $category) {

            $courseCount = $categoryRepo->countCourses($category->id);
            $category->course_count = $courseCount;
            $category->update();

            $parentId = $category->parent_id;

            if (isset($mapping[$parentId])) {
                $mapping[$parentId] += $courseCount;
            } else {
                $mapping[$parentId] = $courseCount;
            }
        }

        /**
         * @var CategoryModel[] $topCategories
         */
        $topCategories = $categoryRepo->findAll(['level' => 1, 'deleted' => 0]);

        foreach ($topCategories as $category) {
            if (isset($mapping[$category->id])) {
                $category->course_count = $mapping[$category->id];
                $category->update();
            }
        }
    }

}
