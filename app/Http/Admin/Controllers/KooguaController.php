<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Traits\Response as ResponseTrait;
use App\Traits\Service as ServiceTrait;

/**
 * @RoutePrefix("/admin/koogua")
 */
class KooguaController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;
    use ServiceTrait;

    /**
     * @Get("/wiki", name="admin.koogua.wiki")
     */
    public function wikiAction()
    {
        $url = 'https://www.koogua.com/page/wiki';

        return $this->response->redirect($url, true);
    }

}
