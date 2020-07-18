<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Course as CourseModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\Package as PackageModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Package extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(PackageModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
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

    /**
     * @param int $id
     * @return PackageModel|Model|bool
     */
    public function findById($id)
    {
        return PackageModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|PackageModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return PackageModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param string $packageId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findCourses($packageId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CoursePackageModel::class, 'c.id = cp.course_id', 'cp')
            ->where('cp.package_id = :package_id:', ['package_id' => $packageId])
            ->andWhere('c.published = 1')
            ->getQuery()
            ->execute();
    }

    public function countCourses($packageId)
    {
        return (int)CoursePackageModel::count([
            'conditions' => 'package_id = :package_id:',
            'bind' => ['package_id' => $packageId],
        ]);
    }

}
