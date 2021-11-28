<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Help as HelpService;
use App\Models\Category as CategoryModel;

/**
 * @RoutePrefix("/admin/help")
 */
class HelpController extends Controller
{

    /**
     * @Get("/category", name="admin.help.category")
     */
    public function categoryAction()
    {
        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['type' => CategoryModel::TYPE_HELP]
        );

        return $this->response->redirect($location);
    }

    /**
     * @Get("/list", name="admin.help.list")
     */
    public function listAction()
    {
        $helpService = new HelpService();

        $helps = $helpService->getHelps();

        $this->view->setVar('helps', $helps);
    }

    /**
     * @Get("/add", name="admin.help.add")
     */
    public function addAction()
    {
        $helpService = new HelpService();

        $categories = $helpService->getCategories();

        $this->view->setVar('categories', $categories);
    }

    /**
     * @Post("/create", name="admin.help.create")
     */
    public function createAction()
    {
        $helpService = new HelpService();

        $helpService->createHelp();

        $location = $this->url->get(['for' => 'admin.help.list']);

        $content = [
            'location' => $location,
            'msg' => '创建帮助成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.help.edit")
     */
    public function editAction($id)
    {
        $helpService = new HelpService();

        $categories = $helpService->getCategories();

        $help = $helpService->getHelp($id);

        $this->view->setVar('categories', $categories);
        $this->view->setVar('help', $help);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.help.update")
     */
    public function updateAction($id)
    {
        $helpService = new HelpService();

        $helpService->updateHelp($id);

        $location = $this->url->get(['for' => 'admin.help.list']);

        $content = [
            'location' => $location,
            'msg' => '更新帮助成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.help.delete")
     */
    public function deleteAction($id)
    {
        $helpService = new HelpService();

        $helpService->deleteHelp($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除帮助成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.help.restore")
     */
    public function restoreAction($id)
    {
        $helpService = new HelpService();

        $helpService->restoreHelp($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原帮助成功',
        ];

        return $this->jsonSuccess($content);
    }

}
