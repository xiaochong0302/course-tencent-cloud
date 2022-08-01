<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\User\AnswerList as AnswerListService;
use App\Services\Logic\User\ArticleList as ArticleListService;
use App\Services\Logic\User\CourseList as CourseListService;
use App\Services\Logic\User\QuestionList as QuestionListService;
use App\Services\Logic\User\UserInfo as UserInfoService;

/**
 * @RoutePrefix("/api/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="api.user.info")
     */
    public function infoAction($id)
    {
        $service = new UserInfoService();

        $user = $service->handle($id);

        if ($user['deleted'] == 1) {
            $this->notFound();
        }

        return $this->jsonSuccess(['user' => $user]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="api.user.courses")
     */
    public function coursesAction($id)
    {
        $service = new CourseListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/articles", name="api.user.articles")
     */
    public function articlesAction($id)
    {
        $service = new ArticleListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/questions", name="api.user.questions")
     */
    public function questionsAction($id)
    {
        $service = new QuestionListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/answers", name="api.user.answers")
     */
    public function answersAction($id)
    {
        $service = new AnswerListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

}
