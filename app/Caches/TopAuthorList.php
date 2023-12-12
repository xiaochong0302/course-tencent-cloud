<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Article as ArticleModel;
use App\Models\ArticleLike as ArticleLikeModel;
use App\Repos\User as UserRepo;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class TopAuthorList extends Cache
{

    protected $lifetime = 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'top_author_list';
    }

    public function getContent($id = null)
    {
        $rankings = $this->findWeeklyAuthorRankings();

        if ($rankings->count() > 0) {
            $userIds = kg_array_column($rankings->toArray(), 'author_id');
            return $this->handleUsers($userIds);
        }

        $randOwners = $this->findRandArticleOwners();

        if ($randOwners->count() > 0) {
            $userIds = kg_array_column($randOwners->toArray(), 'owner_id');
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
    protected function findRandArticleOwners($limit = 10)
    {
        return ArticleModel::query()
            ->columns(['owner_id'])
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset
     */
    protected function findWeeklyAuthorRankings($limit = 10)
    {
        $createTime = strtotime('monday this week');

        $columns = [
            'author_id' => 'a.owner_id',
            'like_count' => 'count(al.user_id)',
        ];

        return $this->modelsManager->createBuilder()
            ->columns($columns)
            ->addFrom(ArticleLikeModel::class, 'al')
            ->join(ArticleModel::class, 'al.article_id = a.id', 'a')
            ->where('al.create_time > :create_time:', ['create_time' => $createTime])
            ->groupBy('author_id')
            ->orderBy('like_count DESC')
            ->limit($limit)->getQuery()->execute();
    }

}
