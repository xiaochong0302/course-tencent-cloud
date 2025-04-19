<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Models\Review as ReviewModel;
use App\Services\Logic\Review\ReviewCreate as ReviewCreateService;
use App\Services\Logic\Review\ReviewDelete as ReviewDeleteService;
use App\Services\Logic\Review\ReviewInfo as ReviewInfoService;
use App\Services\Logic\Review\ReviewLike as ReviewLikeService;
use App\Services\Logic\Review\ReviewUpdate as ReviewUpdateService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/review")
 */
class ReviewController extends Controller
{

    /**
     * @Get("/add", name="home.review.add")
     */
    public function addAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.review.edit")
     */
    public function editAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('review', $review);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="home.review.info")
     */
    public function infoAction($id)
    {
        $service = new ReviewInfoService();

        $review = $service->handle($id);

        if ($review['deleted'] == 1) {
            $this->notFound();
        }

        return $this->jsonSuccess(['review' => $review]);
    }

    /**
     * @Post("/create", name="home.review.create")
     */
    public function createAction()
    {
        $service = new ReviewCreateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.reviews']);

        $content = [
            'location' => $location,
            'target' => 'parent',
            'msg' => '发布评价成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.review.update")
     */
    public function updateAction($id)
    {
        $service = new ReviewUpdateService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.reviews']);

        $content = [
            'location' => $location,
            'target' => 'parent',
            'msg' => '更新评价成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.review.delete")
     */
    public function deleteAction($id)
    {
        $service = new ReviewDeleteService();

        $service->handle($id);

        return $this->jsonSuccess(['msg' => '删除评价成功']);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.review.like")
     */
    public function likeAction($id)
    {
        $service = new ReviewLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
