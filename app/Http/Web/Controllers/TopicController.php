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
     * @Get("/{id:[0-9]+}/info", name="web.topic.info")
     */
    public function infoAction($id)
    {
        $service = new TopicInfoService();

        $topic = $service->handle($id);

        return $this->jsonSuccess(['topic' => $topic]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="web.topic.courses")
     */
    public function coursesAction($id)
    {
        $service = new TopicCourseListService();

        $courses = $service->handle($id);

        return $this->jsonSuccess(['courses' => $courses]);
    }

}
