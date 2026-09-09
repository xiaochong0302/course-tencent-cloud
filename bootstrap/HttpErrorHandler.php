<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\NotFound as NotFoundException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Library\Logger as AppLogger;
use Phalcon\Logger\Logger;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{

    public function __construct()
    {
        parent::__construct();
    }

    public function handleException(Throwable $e): void
    {
        $this->setStatusCode($e);

        if ($this->response->getStatusCode() === 500) {
            $this->report($e);
        }

        if ($this->request->isApi()) {
            $this->apiError($e);
        } elseif ($this->request->isAjax()) {
            $this->ajaxError($e);
        } else {
            $this->pageError($e);
        }
    }

    protected function setStatusCode(Throwable $e): void
    {
        if ($e instanceof BadRequestException) {
            $this->response->setStatusCode(400);
        } elseif ($e instanceof UnauthorizedException) {
            $this->response->setStatusCode(401);
        } elseif ($e instanceof ForbiddenException) {
            $this->response->setStatusCode(403);
        } elseif ($e instanceof NotFoundException) {
            $this->response->setStatusCode(404);
        } elseif ($e instanceof ServiceUnavailableException) {
            $this->response->setStatusCode(503);
        } else {
            $this->response->setStatusCode(500);
        }
    }

    protected function report(Throwable $e): void
    {
        $content = sprintf('%s(%d): %s', $e->getFile(), $e->getLine(), $e->getMessage());

        $logger = $this->getLogger();

        $logger->error($content);

        $config = $this->getConfig();

        if ($config->path('env') === 'dev' || $config->path('log.trace')) {
            $content = sprintf('Trace Content: %s', $e->getTraceAsString());
            $logger->error($content);
        }
    }

    protected function apiError(Throwable $e): void
    {
        $content = $this->translate($e);

        $this->response->setJsonContent($content);
        $this->response->send();
    }

    protected function ajaxError(Throwable $e): void
    {
        $content = $this->translate($e);

        $this->response->setJsonContent($content);
        $this->response->send();
    }

    protected function pageError(Throwable $e): void
    {
        $content = $this->translate($e);

        $this->flashSession->error($content['msg']);

        $code = $this->response->getStatusCode();

        $for = "home.error.{$code}";

        $this->response->redirect(['for' => $for])->send();
    }

    protected function translate(Throwable $e): array
    {
        $errors = require config_path('errors.php');

        $code = $e->getMessage();

        $msg = $errors[$code] ?? $code;

        return [
            'code' => $code,
            'msg' => $msg,
        ];
    }

    protected function getLogger(): Logger
    {
        $logger = new AppLogger();

        return $logger->getInstance('http');
    }

}
