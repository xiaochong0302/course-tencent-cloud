<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Online as OnlineRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class OnlineStat extends LogicService
{

    use UserTrait;

    /**
     * @var string
     */
    protected string $year;

    /**
     * @var string
     */
    protected string $month;

    public function handle(int $id): array
    {
        $user = $this->checkUserCache($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $this->year = $params['year'] ?? date('Y');
        $this->month = $params['month'] ?? date('m');

        $cache = $this->getCache();

        $keyName = $this->getCacheKey($user->id, $this->year, $this->month);

        $content = $cache->get($keyName);

        if (empty($content)) {

            $params['user_id'] = $user->id;

            $startTime = strtotime("{$this->year}-{$this->month}");

            $endTime = strtotime('+1 month', $startTime);

            $params['create_time'] = [
                date('Y-m-d', $startTime),
                date('Y-m-d', $endTime),
            ];

            $sort = $pagerQuery->getSort();
            $page = $pagerQuery->getPage();
            $limit = $pagerQuery->getLimit();

            $repo = new OnlineRepo();

            $pager = $repo->paginate($params, $sort, $page, $limit);

            $content = $this->handleStats($pager);

            $lifetime = strtotime('tomorrow') - time();

            $cache->set($keyName, $content, $lifetime);
        }

        return $content;
    }

    protected function handleStats(PagerRepoInterface $pager): array
    {
        $items = [];

        $dayCount = date('t', strtotime("{$this->year}-{$this->month}"));

        for ($i = 1; $i <= $dayCount; $i++) {
            $items[] = [
                'day' => $i,
                'online' => 0,
                'active_time' => 0,
            ];
        }

        if ($pager->getTotalItems() == 0) {
            return $items;
        }

        foreach ($pager->getItems() as $item) {
            $index = date('d', $item->create_time) - 1;
            $items[$index]['online'] = 1;
            $items[$index]['active_time'] = $item->active_time;
        }

        return $items;
    }

    protected function getCacheKey(int $userId, string $year, string $month): string
    {
        return sprintf('online-stat-%s-%s-%s', $userId, $year, $month);
    }

}
