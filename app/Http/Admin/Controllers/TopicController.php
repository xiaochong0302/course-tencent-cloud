<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Topic as TopicService;

/**
 * @RoutePrefix("/admin/topic")
 */
class TopicController extends Controller
{

    /**
     * @Get("/list", name="admin.topic.list")
     */
    public function listAction()
    {
        $topicService = new TopicService();

        $pager = $topicService->getTopics();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.topic.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/add", name="admin.topic.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.topic.create")
     */
    public function createAction()
    {
        $topicService = new TopicService();

        $topic = $topicService->createTopic();

        $location = $this->url->get([
            'for' => 'admin.topic.edit',
            'id' => $topic->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建话题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.topic.edit")
     */
    public function editAction($id)
    {
        $topicService = new TopicService();

        $topic = $topicService->getTopic($id);
        $xmCourses = $topicService->getXmCourses($id);

        $this->view->setVar('topic', $topic);
        $this->view->setVar('xm_courses', $xmCourses);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.topic.update")
     */
    public function updateAction($id)
    {
        $topicService = new TopicService();

        $topicService->updateTopic($id);

        $content = ['msg' => '更新话题成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.topic.delete")
     */
    public function deleteAction($id)
    {
        $topicService = new TopicService();

        $topicService->deleteTopic($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除话题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.topic.restore")
     */
    public function restoreAction($id)
    {
        $topicService = new TopicService();

        $topicService->restoreTopic($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原话题成功',
        ];

        return $this->jsonSuccess($content);
    }

}
