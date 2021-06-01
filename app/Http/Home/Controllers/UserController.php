<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\User\AnswerList as UserAnswerListService;
use App\Services\Logic\User\ArticleList as UserArticleListService;
use App\Services\Logic\User\CourseList as UserCourseListService;
use App\Services\Logic\User\FriendList as UserFriendListService;
use App\Services\Logic\User\GroupList as UserGroupListService;
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
        $service = new UserInfoService();

        $user = $service->handle($id);

        $this->seo->prependTitle([$user['name'], '个人主页']);

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

    /**
     * @Get("/{id:[0-9]+}/friends", name="home.user.friends")
     */
    public function friendsAction($id)
    {
        $service = new UserFriendListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-friends';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/friends');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/groups", name="home.user.groups")
     */
    public function groupsAction($id)
    {
        $service = new UserGroupListService();

        $pager = $service->handle($id);

        $pager->target = 'tab-groups';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('user/groups');
        $this->view->setVar('pager', $pager);
    }

}
