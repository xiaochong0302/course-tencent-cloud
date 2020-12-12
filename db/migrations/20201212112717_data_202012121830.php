<?php

declare(strict_types=1);

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
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute("DELETE FROM kg_setting WHERE section = 'wechat.oa'");
    }

}