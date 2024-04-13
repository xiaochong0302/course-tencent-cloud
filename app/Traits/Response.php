<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Traits;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\NotFound as NotFoundException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use Phalcon\Config as Config;
use Phalcon\Di as Di;
use Phalcon\Http\Request as HttpRequest;
use Phalcon\Http\Response as HttpResponse;

trait Response
{

    public function unauthorized()
    {
        throw new UnauthorizedException('sys.unauthorized');
    }

    public function forbidden()
    {
        throw new ForbiddenException('sys.forbidden');
    }

    public function notFound()
    {
        throw new NotFoundException('sys.not_found');
    }

    public function setCors()
    {
        /**
         * @var Config $config
         */
        $config = Di::getDefault()->getShared('config');

        $cors = $config->get('cors')->toArray();

        if (!$cors['enabled']) return;

        if (is_array($cors['allow_headers'])) {
            $cors['allow_headers'] = implode(',', $cors['allow_headers']);
        }

        if (is_array($cors['allow_methods'])) {
            $cors['allow_methods'] = implode(',', $cors['allow_methods']);
        }

        /**
         * @var HttpRequest $request
         */
        $request = Di::getDefault()->getShared('request');

        $origin = $request->getHeader('Origin');

        if (is_array($cors['allow_origin']) && in_array($origin, $cors['allow_origin'])) {
            $cors['allow_origin'] = $origin;
        }

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        $response->setHeader('Access-Control-Allow-Origin', $cors['allow_origin']);

        if ($request->isOptions()) {
            $response->setHeader('Access-Control-Allow-Headers', $cors['allow_headers']);
            $response->setHeader('Access-Control-Allow-Methods', $cors['allow_methods']);
        }
    }

    public function jsonSuccess($content = [])
    {
        $content['code'] = 0;

        $content['msg'] = $content['msg'] ?? '';

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        $response->setStatusCode(200);

        $response->setJsonContent($content);

        return $response;
    }

    public function jsonError($content = [])
    {
        $content['code'] = $content['code'] ?? 1;

        $content['msg'] = $content['msg'] ?? $this->getErrorMessage($content['code']);

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        $response->setJsonContent($content);

        return $response;
    }

    public function jsonPaginate($paginate)
    {
        $items = $paginate->items ?? [];
        $totalItems = $paginate->total_items ?? 0;
        $totalPages = $paginate->total_pages ?? 0;

        $pager = [
            'items' => $items,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
        ];

        return $this->jsonSuccess(['pager' => $pager]);
    }

    public function getErrorMessage($code)
    {
        $errors = require config_path('errors.php');

        return $errors[$code] ?? $code;
    }

}
