<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Topic\CourseList as TopicCourseListService;
use App\Services\Frontend\Topic\TopicInfo as TopicInfoService;

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

        $service = new TopicCourseListService();

        $courses = $service->handle($id);

        $this->view->setVar('topic', $topic);
        $this->view->setVar('courses', $courses);
    }

}
