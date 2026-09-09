<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/api")
 */
class PublicController extends Controller
{

    use ResponseTrait;

    /**
     * @Options("/{match:(.*)}", name="api.match_options")
     */
    public function corsAction()
    {
        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @Get("/now", name="api.now")
     */
    public function nowAction()
    {
        return $this->jsonSuccess(['now' => time()]);
    }

}
