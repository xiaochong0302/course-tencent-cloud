<?php

namespace Bootstrap;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\NotFound as NotFoundException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Library\Logger as AppLogger;
use Phalcon\Mvc\User\Component;

class HttpErrorHandler extends Component
{

    public function __construct()
    {
        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);
    }

    public function handleError($severity, $message, $file, $line)
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    /**
     * @param \Throwable $e
     */
    public function handleException($e)
    {
        $this->setStatusCode($e);

        if ($this->response->getStatusCode() == 500) {
            $this->report($e);
        }

        $isApiRequest = is_api_request();
        $isAjaxRequest = is_ajax_request();

        if ($isApiRequest) {
            $this->apiError($e);
        } elseif ($isAjaxRequest) {
            $this->ajaxError($e);
        } else {
            $this->pageError($e);
        }
    }

    /**
     * @param \Throwable $e
     */
    protected function setStatusCode($e)
    {
        if ($e instanceof BadRequestException) {
            $this->response->setStatusCode(400);
        } elseif ($e instanceof UnauthorizedException) {
            $this->response->setStatusCode(401);
        } elseif ($e instanceof ForbiddenException) {
            $this->response->setStatusCode(403);
        } elseif ($e instanceof NotFoundException) {
            $this->response->setStatusCode(404);
        } else {
            $this->response->setStatusCode(500);
        }
    }

    /**
     * @param \Throwable $e
     */
    protected function report($e)
    {
        $content = sprintf('%s(%d): %s', $e->getFile(), $e->getLine(), $e->getMessage());

        $logger = $this->getLogger();

        $logger->error($content);
    }

    /**
     * @param \Throwable $e
     */
    protected function apiError($e)
    {
        $content = $this->translate($e->getMessage());

        $this->response->setJsonContent($content);

        $this->response->send();
    }

    /**
     * @param \Throwable $e
     */
    protected function ajaxError($e)
    {
        $content = $this->translate($e->getMessage());

        $this->response->setJsonContent($content);

        $this->response->send();
    }

    /**
     * @param \Throwable $e
     */
    protected function pageError($e)
    {
        $content = $this->translate($e->getMessage());

        $this->flash->error($content);

        $code = $this->response->getStatusCode();

        $for = "web.error.{$code}";

        $this->response->redirect(['for' => $for])->send();
    }

    protected function translate($code)
    {
        $errors = require config_path() . '/errors.php';

        return [
            'code' => $code,
            'msg' => $errors[$code] ?? $code,
        ];
    }

    protected function getLogger()
    {
        $logger = new AppLogger();

        return $logger->getInstance('http');
    }

}
