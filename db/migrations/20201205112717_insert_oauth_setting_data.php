<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'oauth.qq'])
            ->execute();

        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'oauth.weixin'])
            ->execute();

        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'oauth.weibo'])
            ->execute();
    }

}