<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\WeChat;

use App\Services\WeChatNotice;

class ConsultReply extends WeChatNotice
{

    protected $templateCode = 'consult_reply';

    /**
     * @param array $params
     * @return bool|null
     */
    public function handle(array $params)
    {
        $subscribe = $this->getConnect($params['user']['id']);

        if (!$subscribe) return null;

        $first = sprintf('%s 回复了你的咨询！', $params['replier']['name']);

        $remark = '如果还有其它疑问，请和我们保持联系哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'keyword1' => kg_substr($params['consult']['question'], 0, 50),
            'keyword2' => date('Y-m-d H:i', $params['consult']['create_time']),
            'keyword3' => kg_substr($params['consult']['answer'], 0, 50),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
