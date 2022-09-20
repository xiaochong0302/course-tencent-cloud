<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\WeChat;

use App\Models\WeChatSubscribe as WeChatSubscribeModel;
use App\Services\WeChatNotice;

class AccountLogin extends WeChatNotice
{

    protected $templateCode = 'account_login';

    /**
     * @param WeChatSubscribeModel $subscribe
     * @param array $params
     * @return bool
     */
    public function handle(WeChatSubscribeModel $subscribe, array $params)
    {
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
            'keyword1' => $loginUser,
            'keyword2' => $loginTime,
            'keyword3' => $loginRegion,
            'keyword4' => $loginIp,
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
