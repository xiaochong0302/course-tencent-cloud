<?php

namespace App\Services\Wechat\Notice;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Services\WechatNotice;

class LiveBegin extends WechatNotice
{

    protected $templateCode = 'live_begin';

    /**
     * @param WechatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WechatSubscribeModel $subscribe, array $params)
    {
        $first = '你参与的课程直播就要开始了！';
        $remark = '如果没能参与直播，记得观看直播录像哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['course']['title'],
            'keyword2' => $params['chapter']['title'],
            'keyword3' => date('Y-m-d H:i', $params['live']['start_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
