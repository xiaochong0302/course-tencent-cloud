<?php

use Phinx\Migration\AbstractMigration;

final class Data202012121830 extends AbstractMigration
{

    public function up()
    {
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
                'item_value' => '{"account_login":"","order_finish":"","refund_finish":"","live_begin":"","consult_reply":""}',
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
        $table = 'kg_setting';

        $where = ['section' => 'sms', 'item_key' => 'template'];

        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from($table)
            ->where($where)
            ->execute()
            ->fetch('assoc');

        $itemValue = json_decode($setting['item_value'], true);

        $newItemValue = json_encode([
            'verify' => $itemValue['verify'],
            'order_finish' => $itemValue['order'],
            'refund_finish' => $itemValue['refund'],
            'live_begin' => $itemValue['live'],
            'consult_reply' => '',
        ]);

        $this->getQueryBuilder()
            ->update($table)
            ->where($where)
            ->set('item_value', $newItemValue)
            ->execute();
    }

}