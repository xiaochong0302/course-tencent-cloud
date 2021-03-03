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
        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => 'sms', 'item_key' => 'template'])
            ->execute()->fetch('assoc');

        if (!$setting) return;

        $itemValue = json_decode($setting['item_value'], true);

        $newItemValue = [];

        /**
         * 更改数据结构
         */
        foreach ($itemValue as $key => $value) {
            $newItemValue[$key]['id'] = $value['id'] ?? $value;
            $newItemValue[$key]['enabled'] = $value['enabled'] ?? 1;
        }

        /**
         * 增加发货通知
         */
        $newItemValue['goods_deliver'] = ['id' => '', 'enabled' => 1];

        $itemValue = json_encode($newItemValue);

        $this->getQueryBuilder()
            ->update('kg_setting')
            ->where(['id' => $setting['id']])
            ->set('item_value', $itemValue)
            ->execute();
    }

    protected function updateWechatNoticeTemplate()
    {
        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => 'wechat.oa', 'item_key' => 'notice_template'])
            ->execute()->fetch('assoc');

        $itemValue = json_decode($setting['item_value'], true);

        $newItemValue = [];

        /**
         * 更改数据结构
         */
        foreach ($itemValue as $key => $value) {
            $newItemValue[$key]['id'] = $value['id'] ?? $value;
            $newItemValue[$key]['enabled'] = $value['enabled'] ?? 1;
        }

        /**
         * 增加发货通知
         */
        $newItemValue['goods_deliver'] = ['id' => '', 'enabled' => 1];

        $itemValue = json_encode($newItemValue);

        $this->getQueryBuilder()
            ->update('kg_setting')
            ->where(['id' => $setting['id']])
            ->set('item_value', $itemValue)
            ->execute();
    }

}
