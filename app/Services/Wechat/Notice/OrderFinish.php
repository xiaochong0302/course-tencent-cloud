<?php

namespace App\Services\Wechat\Notice;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Services\WechatNotice;

class OrderFinish extends WechatNotice
{

    protected $templateCode = 'order_finish';

    /**
     * @param WechatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WechatSubscribeModel $subscribe, $params)
    {

        $first = '订单已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['order']['subject'],
            'keyword2' => $params['order']['sn'],
            'keyword3' => $params['order']['amount'],
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
