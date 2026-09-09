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
use App\Library\Http\Request as HttpRequest;
use App\Library\Http\Response as HttpResponse;
use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Paginator\RepositoryInterface;

trait Response
{

    protected function unauthorized(): void
    {
        throw new UnauthorizedException('sys.unauthorized');
    }

    protected function forbidden(): void
    {
        throw new ForbiddenException('sys.forbidden');
    }

    protected function notFound(): void
    {
        throw new NotFoundException('sys.not_found');
    }

    protected function setCors(): HttpResponse
    {
        /**
         * @var HttpRequest $request
         */
        $request = Di::getDefault()->getShared('request');

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->getShared('response');

        /**
         * @var Config $config
         */
        $config = Di::getDefault()->getShared('config');

        $cors = $config->get('cors')->toArray();

        if (!$cors['enabled']) return $response;

        if (is_array($cors['allow_headers'])) {
            $cors['allow_headers'] = implode(',', $cors['allow_headers']);
        }

        if (is_array($cors['allow_methods'])) {
            $cors['allow_methods'] = implode(',', $cors['allow_methods']);
        }

        $origin = $request->getHeader('Origin');

        if (is_array($cors['allow_origin']) && in_array($origin, $cors['allow_origin'])) {
            $cors['allow_origin'] = $origin;
        }

        $response->setHeader('Access-Control-Allow-Origin', $cors['allow_origin']);

        if ($request->isOptions()) {
            $response->setHeader('Access-Control-Allow-Headers', $cors['allow_headers']);
            $response->setHeader('Access-Control-Allow-Methods', $cors['allow_methods']);
        }

        return $response;
    }

    protected function jsonSuccess(array $content = []): HttpResponse
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

    protected function jsonError(array $content = []): HttpResponse
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

    protected function jsonPaginate(RepositoryInterface $paginate): HttpResponse
    {
        $limit = $paginate->getLimit();
        $totalItems = $paginate->getTotalItems();
        $totalPages = (int)ceil($totalItems / $limit);

        $pager = [
            'items' => $paginate->getItems(),
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
        ];

        return $this->jsonSuccess(['pager' => $pager]);
    }

    private function getErrorMessage(string $code): string
    {
        $errors = require config_path('errors.php');

        return $errors[$code] ?? $code;
    }

}
