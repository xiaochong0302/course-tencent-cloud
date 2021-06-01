<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\ImUser as ImUserModel;
use App\Models\Notification as NotificationModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Models\UserBalance as UserBalanceModel;
use App\Models\UserContact as UserContactModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class User extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(UserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (!empty($where['edu_role'])) {
            $builder->andWhere('edu_role = :edu_role:', ['edu_role' => $where['edu_role']]);
        }

        if (!empty($where['admin_role'])) {
            $builder->andWhere('admin_role = :admin_role:', ['admin_role' => $where['admin_role']]);
        }

        if (isset($where['vip'])) {
            $builder->andWhere('vip = :vip:', ['vip' => $where['vip']]);
        }

        if (isset($where['locked'])) {
            $builder->andWhere('locked = :locked:', ['locked' => $where['locked']]);
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
     * @return UserModel|Model|bool
     */
    public function findById($id)
    {
        return UserModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $name
     * @return UserModel|Model|bool
     */
    public function findByName($name)
    {
        return UserModel::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return UserModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $userId
     * @return UserBalanceModel|Model
     */
    public function findUserBalance($userId)
    {
        return UserBalanceModel::findFirst([
            'conditions' => 'user_id = :user_id:',
            'bind' => ['user_id' => $userId],
        ]);
    }

    /**
     * @param int $userId
     * @return UserContactModel|Model
     */
    public function findUserContact($userId)
    {
        return UserContactModel::findFirst([
            'conditions' => 'user_id = :user_id:',
            'bind' => ['user_id' => $userId],
        ]);
    }

    /**
     * @param int $id
     * @return ImUserModel|Model|bool
     */
    public function findImUser($id)
    {
        return ImUserModel::findFirst($id);
    }

    /**
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findTeachers()
    {
        $eduRole = UserModel::EDU_ROLE_TEACHER;

        return UserModel::query()
            ->where('edu_role = :edu_role:', ['edu_role' => $eduRole])
            ->andWhere('deleted = 0')
            ->execute();
    }

    public function countUsers()
    {
        return (int)UserModel::count();
    }

    public function countVipUsers()
    {
        return (int)UserModel::count(['conditions' => 'vip = 1']);
    }

    public function countCourses($userId)
    {
        return (int)CourseUserModel::count([
            'conditions' => 'user_id = :user_id: AND deleted = 0',
            'bind' => ['user_id' => $userId],
        ]);
    }

    public function countArticles($userId)
    {
        return (int)ArticleModel::count([
            'conditions' => 'owner_id = ?1 AND published = ?2',
            'bind' => [1 => $userId, 2 => ArticleModel::PUBLISH_APPROVED],
        ]);
    }

    public function countQuestions($userId)
    {
        return (int)QuestionModel::count([
            'conditions' => 'owner_id = ?1 AND published = ?2',
            'bind' => [1 => $userId, 2 => QuestionModel::PUBLISH_APPROVED],
        ]);
    }

    public function countAnswers($userId)
    {
        return (int)AnswerModel::count([
            'conditions' => 'owner_id = ?1 AND published = ?2',
            'bind' => [1 => $userId, 2 => AnswerModel::PUBLISH_APPROVED],
        ]);
    }

    public function countUnreadNotifications($userId)
    {
        return (int)NotificationModel::count([
            'conditions' => 'receiver_id = :user_id: AND viewed = 0',
            'bind' => ['user_id' => $userId],
        ]);
    }

}
