<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Traits;

use App\Validators\Security as SecurityValidator;
use Phalcon\Di\Di;
use Phalcon\Http\Request;

trait Security
{

    protected function checkClientAddress(): void
    {
        $validator = new SecurityValidator();

        $validator->checkClientAddress();
    }

    protected function checkCsrfToken(): void
    {
        $validator = new SecurityValidator();

        $validator->checkCsrfToken();
    }

    protected function checkHttpReferer(): void
    {
        $validator = new SecurityValidator();

        $validator->checkHttpReferer();
    }

    protected function checkRateLimit(): void
    {
        $validator = new SecurityValidator();

        $validator->checkRateLimit();
    }

    protected function isNotSafeRequest(): bool
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->getShared('request');

        $method = $request->getMethod();

        $list = ['post', 'put', 'patch', 'delete'];

        return in_array(strtolower($method), $list);
    }

}
