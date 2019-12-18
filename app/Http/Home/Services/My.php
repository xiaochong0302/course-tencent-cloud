<?php

namespace App\Http\Home\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Consult as ConsultModel;
use App\Models\Review as ReviewModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Repos\CourseStudent as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Review as ReviewRepo;
use App\Transformers\ConsultList as ConsultListTransformer;
use App\Transformers\CourseFavoriteList as CourseFavoriteListTransformer;
use App\Transformers\CourseUserList as CourseUserListTransformer;
use App\Transformers\OrderList as OrderListTransformer;
use App\Transformers\ReviewList as ReviewListTransformer;
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

        $favoriteRepo = new CourseFavoriteRepo();

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

            $builder = new CourseUserListTransformer();

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

            $builder = new CourseFavoriteListTransformer();

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

            $builder = new ConsultListTransformer();

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

            $builder = new ReviewListTransformer();

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

            $builder = new OrderListTransformer();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleItems($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
