<?php

class Data202102151130 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'app_secret',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'app_token',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'ts_mobiles',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'cs_mobiles',
                'item_value' => '',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'dingtalk.robot'])
            ->execute();
    }

}
