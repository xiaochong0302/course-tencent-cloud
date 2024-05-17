<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\WeChat;

use App\Services\WeChatNotice;

class RefundFinish extends WeChatNotice
{

    protected $templateCode = 'refund_finish';

    /**
     * @param array $params
     * @return bool
     */
    public function handle(array $params)
    {
        $subscribe = $this->getConnect($params['user']['id']);

        if (!$subscribe) return null;

        $first = '退款已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'character_string5' => $params['refund']['sn'],
            'thing1' => $params['refund']['subject'],
            'amount2' => sprintf('%s元', $params['refund']['amount']),
            'time4' => date('Y-m-d H:i', $params['refund']['update_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
