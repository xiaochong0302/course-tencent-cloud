<?php

namespace App\Services\Wechat\Notice;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Services\WechatNotice;

class RefundFinish extends WechatNotice
{

    protected $templateCode = 'refund_finish';

    /**
     * @param WechatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WechatSubscribeModel $subscribe, array $params)
    {
        $first = '退款已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['refund']['subject'],
            'keyword2' => $params['refund']['sn'],
            'keyword3' => $params['refund']['amount'],
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
