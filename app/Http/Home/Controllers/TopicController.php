<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        if ($topic['published'] == 0) {
            $this->notFound();
        }

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
