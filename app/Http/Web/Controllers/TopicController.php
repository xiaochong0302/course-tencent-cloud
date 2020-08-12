<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Topic\CourseList as TopicCourseListService;
use App\Services\Frontend\Topic\TopicInfo as TopicInfoService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/topic")
 */
class TopicController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="web.topic.show")
     */
    public function showAction($id)
    {
        $service = new TopicInfoService();

        $topic = $service->handle($id);

        $this->seo->prependTitle($topic['title']);
        $this->seo->setDescription($topic['summary']);

        $this->view->setVar('topic', $topic);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="web.topic.courses")
     */
    public function coursesAction($id)
    {
        $service = new TopicCourseListService();

        $pager = $service->handle($id);

        $pager->target = 'course-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('topic/courses');
        $this->view->setVar('pager', $pager);
    }

}
