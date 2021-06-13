<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Teacher\TeacherList as TeacherListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/teacher")
 */
class TeacherController extends Controller
{

    /**
     * @Get("/list", name="home.teacher.list")
     */
    public function listAction()
    {
        $this->seo->prependTitle('教师');
    }

    /**
     * @Get("/pager", name="home.teacher.pager")
     */
    public function pagerAction()
    {
        $service = new TeacherListService();

        $pager = $service->handle();

        $pager->target = 'teacher-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.teacher.show")
     */
    public function showAction($id)
    {
        $this->dispatcher->forward([
            'controller' => 'user',
            'action' => 'show',
            'params' => ['id' => $id],
        ]);
    }

}
