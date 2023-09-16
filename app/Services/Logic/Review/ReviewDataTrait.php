<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Review;

use App\Traits\Client as ClientTrait;
use App\Validators\Review as ReviewValidator;

trait ReviewDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new ReviewValidator();

        $data['content'] = $validator->checkContent($post['content']);
        $data['rating1'] = $validator->checkRating($post['rating1']);
        $data['rating2'] = $validator->checkRating($post['rating2']);
        $data['rating3'] = $validator->checkRating($post['rating3']);

        if (isset($post['anonymous'])) {
            $data['anonymous'] = $validator->checkAnonymous($post['anonymous']);
        }

        return $data;
    }

}
