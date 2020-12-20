<?php

namespace App\Services\Wechat\Notice;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Services\WechatNotice;

class ConsultReply extends WechatNotice
{

    protected $templateCode = 'consult_reply';

    /**
     * @param WechatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WechatSubscribeModel $subscribe, array $params)
    {
        $openId = $subscribe->open_id;

        $templateId = $this->getTemplateId($this->templateCode);

        $first = sprintf('%s 回复了你的咨询！', $params['replier']['name']);

        $remark = '如果还有其它疑问，请和我们保持联系哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['course']['title'],
        ];

        return $this->send($openId, $templateId, $params);
    }

}
