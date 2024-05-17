<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\WeChat;

use App\Services\WeChatNotice;

class AccountLogin extends WeChatNotice
{

    protected $templateCode = 'account_login';

    /**
     * @param array $params
     * @return bool|null
     */
    public function handle(array $params)
    {
        $subscribe = $this->getConnect($params['user']['id']);

        if (!$subscribe) return null;

        $first = '你好，登录系统成功！';
        $remark = '如果非本人操作，请立即修改密码哦！';

        $loginRegion = implode('/', [
            $params['login_region']['country'],
            $params['login_region']['province'],
            $params['login_region']['city'],
        ]);

        $loginTime = date('Y-m-d H:i', $params['login_time']);
        $loginUser = $params['user']['name'];
        $loginIp = $params['login_ip'];

        $params = [
            'first' => $first,
            'remark' => $remark,
            'thing3' => $loginUser,
            'time2' => $loginTime,
            'thing7' => $loginRegion,
            'character_string8' => $loginIp,
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
