<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Traits;

use App\Validators\Security as SecurityValidator;
use Phalcon\Di;
use Phalcon\Http\Request;

trait Security
{

    public function checkCsrfToken()
    {
        $validator = new SecurityValidator();

        $validator->checkCsrfToken();
    }

    public function checkHttpReferer()
    {
        $validator = new SecurityValidator();

        $validator->checkHttpReferer();
    }

    public function isNotSafeRequest()
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
