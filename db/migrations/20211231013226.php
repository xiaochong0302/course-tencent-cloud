<?php

use Phinx\Migration\AbstractMigration;

final class V20211231013226 extends AbstractMigration
{

    public function up()
    {
        $this->handleSmsSetting();
    }

    protected function handleSmsSetting()
    {
        $row =
            [
                [
                    'section' => 'sms',
                    'item_key' => 'region',
                    'item_value' => 'ap-guangzhou',
                ]
            ];

        $this->table('kg_setting')->insert($row)->save();
    }

}
