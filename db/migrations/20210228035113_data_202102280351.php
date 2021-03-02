<?php

use Phinx\Migration\AbstractMigration;

final class Data202102280351 extends AbstractMigration
{

    public function up()
    {
        $this->updateSmsNoticeTemplate();
        $this->updateWechatNoticeTemplate();
    }

    protected function updateSmsNoticeTemplate()
    {
        $table = 'kg_setting';

        $where = [
            'section' => 'sms',
            'item_key' => 'template',
        ];

        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from($table)
            ->where($where)
            ->execute()
            ->fetch('assoc');

        $itemValue = json_decode($setting['item_value'], true);

        $newItemValue = [];

        foreach ($itemValue as $key => $value) {
            $newItemValue[$key]['id'] = $value;
            $newItemValue[$key]['enabled'] = 1;
        }

        /**
         * 增加发货通知
         */
        $newItemValue['goods_deliver'] = ['id' => '', 'enabled' => 1];

        $itemValue = json_encode($newItemValue);

        $this->getQueryBuilder()
            ->update($table)
            ->where($where)
            ->set('item_value', $itemValue)
            ->execute();
    }

    protected function updateWechatNoticeTemplate()
    {
        $table = 'kg_setting';

        $where = [
            'section' => 'wechat.oa',
            'item_key' => 'notice_template',
        ];

        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from($table)
            ->where($where)
            ->execute()
            ->fetch('assoc');

        $itemValue = json_decode($setting['item_value'], true);

        $newItemValue = [];

        foreach ($itemValue as $key => $value) {
            $newItemValue[$key]['id'] = $value;
            $newItemValue[$key]['enabled'] = 1;
        }

        /**
         * 增加发货通知
         */
        $newItemValue['goods_deliver'] = ['id' => '', 'enabled' => 1];

        $itemValue = json_encode($newItemValue);

        $this->getQueryBuilder()
            ->update($table)
            ->where($where)
            ->set('item_value', $itemValue)
            ->execute();
    }

}
