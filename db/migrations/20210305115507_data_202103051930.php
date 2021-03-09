<?php

use Phinx\Migration\AbstractMigration;

final class Data202103051930 extends AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'section' => 'pay.alipay',
                'item_key' => 'service_rate',
                'item_value' => 5,
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'service_rate',
                'item_value' => 5,
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'pay.alipay', 'item_key' => 'service_rate'])
            ->execute();

        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'pay.wxpay', 'item_key' => 'service_rate'])
            ->execute();
    }

}
