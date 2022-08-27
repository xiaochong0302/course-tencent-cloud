<?php

use Phinx\Migration\AbstractMigration;

require_once 'SettingTrait.php';

final class V20211231013226 extends AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->handleSmsSettings();
    }

    protected function handleSmsSettings()
    {
        $rows = [
            [
                'section' => 'sms',
                'item_key' => 'region',
                'item_value' => 'ap-guangzhou',
            ]
        ];

        $this->insertSettings($rows);
    }

}
