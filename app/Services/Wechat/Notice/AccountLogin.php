<?php

namespace App\Services\Wechat\Notice;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Services\WechatNotice;

class AccountLogin extends WechatNotice
{

    protected $templateCode = 'account_login';

    /**
     * @param WechatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WechatSubscribeModel $subscribe, array $params)
    {
        $first = '你好，登录系统成功！';
        $remark = '如果非本人操作，请立即修改密码哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => $params['login_region'],
            'keyword2' => date('Y-m-d H:i', $params['login_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
