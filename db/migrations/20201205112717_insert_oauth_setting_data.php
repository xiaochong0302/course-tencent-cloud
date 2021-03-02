<?php

use Phinx\Migration\AbstractMigration;

final class InsertOauthSettingData extends AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'section' => 'oauth.qq',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'redirect_uri',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'redirect_uri',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'redirect_uri',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'refuse_uri',
                'item_value' => '',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute("DELETE FROM kg_setting WHERE section = 'oauth.qq'");
        $this->execute("DELETE FROM kg_setting WHERE section = 'oauth.weixin'");
        $this->execute("DELETE FROM kg_setting WHERE section = 'oauth.weibo'");
    }

}