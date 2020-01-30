<?php

namespace App\Http\Home\Services;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Builders\OrderList as OrderListBuilder;
use App\Builders\ReviewList as ReviewListBuilder;
use App\Builders\UserFavoriteList as FavoriteListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Consult as ConsultModel;
use App\Models\Review as ReviewModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\CourseFavorite as FavoriteRepo;
use App\Repos\CourseStudent as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Review as ReviewRepo;
use App\Validators\Order as OrderFilter;

class My extends Service
{

    public function getCourses()
    {
        $user = $this->getLoggedUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = ['user_id' => $user->id];

        $courseUserRepo = new CourseUserRepo();

        $pager = $courseUserRepo->paginate($where, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    public function getFavorites()
    {
        $user = $this->getLoggedUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = ['user_id' => $user->id];

        $favoriteRepo = new FavoriteRepo();

        $pager = $favoriteRepo->paginate($where, $sort, $page, $limit);

        return $this->handleFavorites($pager);
    }

    public function getConsults()
    {
        $user = $this->getLoggedUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'user_id' => $user->id,
            'status' => ConsultModel::STATUS_NORMAL,
        ];

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($where, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    public function getReviews()
    {
        $user = $this->getLoggedUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'user_id' => $user->id,
            'status' => ReviewModel::STATUS_NORMAL,
        ];

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($where, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    public function getOrders()
    {
        $user = $this->getLoggedUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $filter = new OrderFilter();

        $where = [];

        $where['user_id'] = $user->id;

        if (!empty($params['status'])) {
            $where['status'] = $filter->checkStatus($params['status']);
        }

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($where, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    private function handleCourses($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseUserListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleCourses($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleFavorites($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new FavoriteListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleCourses($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleConsults($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ConsultListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleCourses($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleReviews($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleCourses($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleOrders($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new OrderListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleItems($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
