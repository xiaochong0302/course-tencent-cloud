<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\WeChat;

use App\Services\WeChatNotice;

class GoodsDeliver extends WeChatNotice
{

    protected $templateCode = 'goods_deliver';

    /**
     * @param array $params
     * @return bool|null
     */
    public function handle($params)
    {
        $subscribe = $this->getConnect($params['user']['id']);

        if (!$subscribe) return null;

        $first = '发货已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'character_string12' => $params['order_sn'],
            'thing14' => $params['goods_name'],
            'time3' => date('Y-m-d H:i', $params['deliver_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
