<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Answer as AnswerModel;
use App\Models\AnswerLike as AnswerLikeModel;
use App\Repos\User as UserRepo;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class TopAnswererList extends Cache
{

    protected $lifetime = 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'top_answerer_list';
    }

    public function getContent($id = null)
    {
        $rankings = $this->findWeeklyAuthorRankings();

        if ($rankings->count() > 0) {
            $userIds = kg_array_column($rankings->toArray(), 'author_id');
            return $this->handleUsers($userIds);
        }

        $rankings = $this->findMonthlyAuthorRankings();

        if ($rankings->count() > 0) {
            $userIds = kg_array_column($rankings->toArray(), 'author_id');
            return $this->handleUsers($userIds);
        }

        $rankings = $this->findYearlyAuthorRankings();

        if ($rankings->count() > 0) {
            $userIds = kg_array_column($rankings->toArray(), 'author_id');
            return $this->handleUsers($userIds);
        }

        $rankings = $this->findFullyAuthorRankings();

        if ($rankings->count() > 0) {
            $userIds = kg_array_column($rankings->toArray(), 'author_id');
            return $this->handleUsers($userIds);
        }

        return [];
    }

    protected function handleUsers($userIds)
    {
        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($userIds);

        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'title' => $user->title,
                'about' => $user->about,
                'vip' => $user->vip,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset
     */
    protected function findWeeklyAuthorRankings($limit = 10)
    {
        $createTime = strtotime('monday this week');

        return $this->findAuthorRankings($createTime, $limit);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset
     */
    protected function findMonthlyAuthorRankings($limit = 10)
    {
        $createTime = strtotime(date('Y-m-01'));

        return $this->findAuthorRankings($createTime, $limit);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset
     */
    protected function findYearlyAuthorRankings($limit = 10)
    {
        $createTime = strtotime(date('Y-01-01'));

        return $this->findAuthorRankings($createTime, $limit);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset
     */
    protected function findFullyAuthorRankings($limit = 10)
    {
        $createTime = 0;

        return $this->findAuthorRankings($createTime, $limit);
    }

    /**
     * @param int $createTime
     * @param int $limit
     * @return ResultsetInterface|Resultset
     */
    protected function findAuthorRankings($createTime, $limit = 10)
    {
        $columns = [
            'author_id' => 'a.owner_id',
            'like_count' => 'count(al.user_id)',
        ];

        return $this->modelsManager->createBuilder()
            ->columns($columns)
            ->addFrom(AnswerLikeModel::class, 'al')
            ->join(AnswerModel::class, 'al.answer_id = a.id', 'a')
            ->where('al.create_time > :create_time:', ['create_time' => $createTime])
            ->groupBy('author_id')
            ->orderBy('like_count DESC')
            ->limit($limit)->getQuery()->execute();
    }

}
