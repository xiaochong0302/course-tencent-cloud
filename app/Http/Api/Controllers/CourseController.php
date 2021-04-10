<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Course\CategoryList as CourseCategoryListService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;
use App\Services\Logic\Course\ConsultList as CourseConsultListService;
use App\Services\Logic\Course\CourseFavorite as CourseFavoriteService;
use App\Services\Logic\Course\CourseInfo as CourseInfoService;
use App\Services\Logic\Course\CourseList as CourseListService;
use App\Services\Logic\Course\PackageList as CoursePackageListService;
use App\Services\Logic\Course\ReviewList as CourseReviewListService;

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
        $service = new CourseCategoryListService();

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

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.course.info")
     */
    public function infoAction($id)
    {
        $service = new CourseInfoService();

        $course = $service->handle($id);

        return $this->jsonSuccess(['course' => $course]);
    }

    /**
     * @Get("/{id:[0-9]+}/chapters", name="api.course.chapters")
     */
    public function chaptersAction($id)
    {
        $service = new CourseChapterListService();

        $chapters = $service->handle($id);

        return $this->jsonSuccess(['chapters' => $chapters]);
    }

    /**
     * @Get("/{id:[0-9]+}/packages", name="api.course.packages")
     */
    public function packagesAction($id)
    {
        $service = new CoursePackageListService();

        $packages = $service->handle($id);

        return $this->jsonSuccess(['packages' => $packages]);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="api.course.consults")
     */
    public function consultsAction($id)
    {
        $service = new CourseConsultListService();

        $pager = $service->handle($id);

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/{id:[0-9]+}/reviews", name="api.course.reviews")
     */
    public function reviewsAction($id)
    {
        $service = new CourseReviewListService();

        $pager = $service->handle($id);

        return $this->jsonSuccess(['pager' => $pager]);
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

    /**
     * @Post("/{id:[0-9]+}/unfavorite", name="api.course.unfavorite")
     */
    public function unfavoriteAction($id)
    {
        $service = new CourseFavoriteService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '收藏成功' : '取消收藏成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
