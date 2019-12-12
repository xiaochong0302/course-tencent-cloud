<?php

namespace Bootstrap;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\NotFound as NotFoundException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use Phalcon\Mvc\User\Component as UserComponent;
use Phalcon\Text;

class HttpErrorHandler extends UserComponent
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getDI()->get('logger');

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);
    }

    public function handleError($no, $str, $file, $line)
    {
        $content = compact('no', 'str', 'file', 'line');

        $error = json_encode($content);

        $this->logger->log($error);
    }

    public function handleException($e)
    {
        $this->setStatusCode($e);

        if ($this->router->getModuleName() == 'api') {
            $this->apiError($e);
        } else if ($this->isAjax()) {
            $this->ajaxError($e);
        } else {
            $this->pageError($e);
        }
    }

    protected function setStatusCode($e)
    {
        if ($e instanceof BadRequestException) {
            $this->response->setStatusCode(400);
        } else if ($e instanceof UnauthorizedException) {
            $this->response->setStatusCode(401);
        } else if ($e instanceof ForbiddenException) {
            $this->response->setStatusCode(403);
        } else if ($e instanceof NotFoundException) {
            $this->response->setStatusCode(404);
        } else {
            $this->response->setStatusCode(500);
            $this->report($e);
        }
    }

    protected function report($e)
    {
        $content = sprintf('%s(%d): %s', $e->getFile(), $e->getLine(), $e->getMessage());

        $this->logger->error($content);
    }

    protected function apiError($e)
    {
        $content = $this->translate($e->getMessage());

        $this->response->setJsonContent($content);
        $this->response->send();
    }

    protected function ajaxError($e)
    {
        $content = $this->translate($e->getMessage());

        $this->response->setJsonContent($content);
        $this->response->send();
    }

    protected function pageError($e)
    {
        $content = $this->translate($e->getMessage());

        $this->flash->error($content);

        $this->response->redirect([
            'for' => 'error.' . $this->response->getStatusCode()
        ])->send();
    }

    protected function translate($code)
    {
        $errors = require config_path() . '/errors.php';

        $content = [
            'code' => $code,
            'msg' => $errors[$code] ?? $code,
        ];

        return $content;
    }

    protected function isAjax()
    {
        if ($this->request->isAjax()) {
            return true;
        }

        $contentType = $this->request->getContentType();

        if (Text::startsWith($contentType, 'application/json')) {
            return true;
        }

        return false;
    }

}
