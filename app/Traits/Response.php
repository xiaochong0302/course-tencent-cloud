<?php

namespace App\Traits;

use Phalcon\Di;
use Phalcon\Http\Response as HttpResponse;

trait Response
{

    public function jsonSuccess($content = [])
    {
        $content['code'] = 0;

        $content['msg'] = $content['msg'] ?? '';

        /**
         * @var HttpResponse $response
         */
        $response = Di::getDefault()->get('response');

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
        $response = Di::getDefault()->get('response');

        $response->setJsonContent($content);

        return $response;
    }

    public function jsonPaginate($paginate)
    {
        $items = $paginate->items ?? [];
        $totalItems = $paginate->total_items ?? 0;
        $totalPages = $paginate->total_pages ?? 0;

        $content = [
            'items' => $items,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
        ];

        return $this->jsonSuccess($content);
    }

    public function getErrorMessage($code)
    {
        $errors = require config_path() . '/errors.php';

        $message = $errors[$code] ?? $code;

        return $message;
    }

}