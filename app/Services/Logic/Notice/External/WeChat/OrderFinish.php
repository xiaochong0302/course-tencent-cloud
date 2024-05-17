<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\WeChat;

use App\Services\WeChatNotice;

class OrderFinish extends WeChatNotice
{

    protected $templateCode = 'order_finish';

    /**
     * @param array $params
     * @return bool|null
     */
    public function handle($params)
    {
        $subscribe = $this->getConnect($params['user']['id']);

        if (!$subscribe) return null;

        $first = '订单已处理完成！';
        $remark = '感谢您的支持，有疑问请联系客服哦！';

        $params = [
            'first' => $first,
            'remark' => $remark,
            'amount3' => sprintf('%s元', $params['order']['amount']),
            'thing1' => $params['order']['subject'],
            'time4' => date('Y-m-d H:i', $params['order']['update_time']),
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($subscribe->open_id, $templateId, $params);
    }

}
