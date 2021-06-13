<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class Data202012121830 extends AbstractMigration
{

    public function up()
    {
        $noticeTemplate = json_encode([
            'account_login' => '',
            'order_finish' => '',
            'refund_finish' => '',
            'live_begin' => '',
            'consult_reply' => '',
        ]);

        $rows = [
            [
                'section' => 'wechat.oa',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'app_secret',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'app_token',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'aes_key',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'notify_url',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'notice_template',
                'item_value' => $noticeTemplate,
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();

        $this->updateSmsTemplate();
    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'wechat.oa'])
            ->execute();
    }

    protected function updateSmsTemplate()
    {
        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => 'sms', 'item_key' => 'template'])
            ->execute()->fetch('assoc');

        if (!$setting) return;

        $itemValue = json_decode($setting['item_value'], true);

        $newItemValue = json_encode([
            'verify' => $itemValue['verify'] ?? '',
            'order_finish' => $itemValue['order'] ?? '',
            'refund_finish' => $itemValue['refund'] ?? '',
            'live_begin' => $itemValue['live'] ?? '',
            'consult_reply' => $itemValue['consult'] ?? '',
        ]);

        $this->getQueryBuilder()
            ->update('kg_setting')
            ->where(['id' => $setting['id']])
            ->set('item_value', $newItemValue)
            ->execute();
    }

}