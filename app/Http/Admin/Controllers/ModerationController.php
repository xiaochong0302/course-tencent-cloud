<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Moderation as ModerationService;

/**
 * @RoutePrefix("/admin/moderation")
 */
class ModerationController extends Controller
{

    /**
     * @Get("/reviews", name="admin.mod.reviews")
     */
    public function reviewsAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getReviews();

        $this->view->setVar('pager', $pager);
    }

}
