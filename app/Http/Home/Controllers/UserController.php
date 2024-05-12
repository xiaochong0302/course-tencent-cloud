<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\User\AnswerList as UserAnswerListService;
use App\Services\Logic\User\ArticleList as UserArticleListService;
use App\Services\Logic\User\CourseList as UserCourseListService;
use App\Services\Logic\User\QuestionList as UserQuestionListService;
use App\Services\Logic\User\UserInfo as UserInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/user")
 */
class UserController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.user.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getUserIndexUrl($id);
            return $this->response->redirect($location);
        }

        $service = new UserInfoService();

        $user = $service->handle($id);

        if ($user['deleted'] == 1) {
            $this->notFound();
        }

        $this->seo->prependTitle(['空间', $user['name']]);

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.user.courses")
     */
    public function coursesAction($id)
    {
        $service = new UserCourseListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-courses';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/courses');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/articles", name="home.user.articles")
     */
    public function articlesAction($id)
    {
        $service = new UserArticleListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-articles';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/articles');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/questions", name="home.user.questions")
     */
    public function questionsAction($id)
    {
        $service = new UserQuestionListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-questions';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/questions');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/answers", name="home.user.answers")
     */
    public function answersAction($id)
    {
        $service = new UserAnswerListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-answers';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/answers');
        $this->view->setVar('pager', $pager);
    }

}
