<?php

class Data202101051130 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'section' => 'site',
                'item_key' => 'logo',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'favicon',
                'item_value' => '',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'site', 'item_key' => 'logo'])
            ->execute();

        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'site', 'item_key' => 'favicon'])
            ->execute();
    }

}
