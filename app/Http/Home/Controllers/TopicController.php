<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Topic\CourseList as TopicCourseListService;
use App\Services\Logic\Topic\TopicInfo as TopicInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/topic")
 */
class TopicController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.topic.show")
     */
    public function showAction($id)
    {
        $service = new TopicInfoService();

        $topic = $service->handle($id);

        $this->seo->prependTitle(['专题', $topic['title']]);
        $this->seo->setDescription($topic['summary']);

        $this->view->setVar('topic', $topic);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.topic.courses")
     */
    public function coursesAction($id)
    {
        $service = new TopicCourseListService();

        $pager = $service->handle($id);

        $pager->target = 'course-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
