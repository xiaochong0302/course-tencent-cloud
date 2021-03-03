<?php

namespace App\Services\Logic\Notice\WeChat;

use App\Models\WeChatSubscribe as WeChatSubscribeModel;
use App\Services\WeChatNotice;

class GoodsDeliver extends WeChatNotice
{

    protected $templateCode = 'goods_deliver';

    /**
     * @param WeChatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WeChatSubscribeModel $subscribe, $params)
    {

        $first = '发货已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['goods_name'],
            'keyword2' => $params['order_sn'],
            'keyword3' => date('Y-m-d H:i', $params['deliver_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
