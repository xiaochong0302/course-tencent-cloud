<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Tag as TagService;

/**
 * @RoutePrefix("/admin/tag")
 */
class TagController extends Controller
{

    /**
     * @Get("/list", name="admin.tag.list")
     */
    public function listAction()
    {
        $tagService = new TagService();

        $pager = $tagService->getTags();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.tag.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/add", name="admin.tag.add")
     */
    public function addAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.tag.edit")
     */
    public function editAction($id)
    {
        $tagService = new TagService;

        $tag2 = $tagService->getTag($id);
        $scopeTypes = $tagService->getScopeTypes();

        /**
         * 注意："tag"变量被volt引擎内置占用，另取名字避免冲突
         */
        $this->view->setVar('tag2', $tag2);
        $this->view->setVar('scope_types', $scopeTypes);
    }

    /**
     * @Post("/create", name="admin.tag.create")
     */
    public function createAction()
    {
        $tagService = new TagService();

        $tagService->createTag();

        $location = $this->url->get(['for' => 'admin.tag.list']);

        $content = [
            'location' => $location,
            'msg' => '创建标签成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.tag.update")
     */
    public function updateAction($id)
    {
        $tagService = new TagService();

        $tagService->updateTag($id);

        $location = $this->url->get(['for' => 'admin.tag.list']);

        $content = [
            'location' => $location,
            'msg' => '更新标签成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.tag.delete")
     */
    public function deleteAction($id)
    {
        $tagService = new TagService();

        $tagService->deleteTag($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除标签成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.tag.restore")
     */
    public function restoreAction($id)
    {
        $tagService = new TagService();

        $tagService->restoreTag($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原标签成功',
        ];

        return $this->jsonSuccess($content);
    }

}
