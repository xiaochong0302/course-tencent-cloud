<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Course\CategoryList as CategoryListService;
use App\Services\Logic\Course\ChapterList as ChapterListService;
use App\Services\Logic\Course\ConsultList as ConsultListService;
use App\Services\Logic\Course\CourseFavorite as CourseFavoriteService;
use App\Services\Logic\Course\CourseInfo as CourseInfoService;
use App\Services\Logic\Course\CourseList as CourseListService;
use App\Services\Logic\Course\PackageList as PackageListService;
use App\Services\Logic\Course\ReviewList as ReviewListService;

/**
 * @RoutePrefix("/api/course")
 */
class CourseController extends Controller
{

    /**
     * @Get("/categories", name="api.course.categories")
     */
    public function categoriesAction()
    {
        $service = new CategoryListService();

        $categories = $service->handle();

        return $this->jsonSuccess(['categories' => $categories]);
    }

    /**
     * @Get("/list", name="api.course.list")
     */
    public function listAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.course.info")
     */
    public function infoAction($id)
    {
        $service = new CourseInfoService();

        $course = $service->handle($id);

        if ($course['deleted'] == 1) {
            $this->notFound();
        }

        if ($course['published'] == 0) {
            $this->notFound();
        }

        return $this->jsonSuccess(['course' => $course]);
    }

    /**
     * @Get("/{id:[0-9]+}/chapters", name="api.course.chapters")
     */
    public function chaptersAction($id)
    {
        $service = new ChapterListService();

        $chapters = $service->handle($id);

        return $this->jsonSuccess(['chapters' => $chapters]);
    }

    /**
     * @Get("/{id:[0-9]+}/packages", name="api.course.packages")
     */
    public function packagesAction($id)
    {
        $service = new PackageListService();

        $packages = $service->handle($id);

        return $this->jsonSuccess(['packages' => $packages]);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="api.course.consults")
     */
    public function consultsAction($id)
    {
        $service = new ConsultListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="api.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new ReviewListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/{id:[0-9]+}/favorite", name="api.course.favorite")
     */
    public function favoriteAction($id)
    {
        $service = new CourseFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
