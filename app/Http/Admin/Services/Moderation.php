<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Review as ReviewModel;
use App\Repos\Review as ReviewRepo;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Moderation extends Service
{

    public function getReviews(): PagerRepoInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = ReviewModel::PUBLISH_PENDING;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    protected function handleReviews(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
