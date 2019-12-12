<?php

namespace App\Traits;

trait Ajax
{

    public function ajaxSuccess($content = [])
    {
        $content['code'] = 0;
        $content['msg'] = $content['msg'] ?? '';

        $this->response->setStatusCode(200);
        $this->response->setJsonContent($content);

        return $this->response;
    }

    public function ajaxError($content = [])
    {
        $content['code'] = $content['code'] ?? 1;
        $content['msg'] = $content['msg'] ?? $this->getErrorMessage($content['code']);

        $this->response->setJsonContent($content);

        return $this->response;
    }

    public function getErrorMessage($code)
    {
        $errors = require config_path() . '/errors.php';

        $message = $errors[$code] ?? $code;

        return $message;
    }

}