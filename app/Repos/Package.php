<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Course as CourseModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\Package as PackageModel;

class Package extends Repository
{

    /**
     * @param int $id
     * @return PackageModel
     */
    public function findById($id)
    {
        $result = PackageModel::findFirst($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = PackageModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(PackageModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    public function findCourses($packageId)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CoursePackageModel::class, 'c.id = cp.course_id', 'cp')
            ->where('cp.package_id = :package_id:', ['package_id' => $packageId])
            ->andWhere('c.deleted = 0')
            ->getQuery()
            ->execute();

        return $result;
    }

    public function countCourses($packageId)
    {
        $count = CoursePackageModel::count([
            'conditions' => 'package_id = :package_id:',
            'bind' => ['package_id' => $packageId],
        ]);

        return $count;
    }

}
