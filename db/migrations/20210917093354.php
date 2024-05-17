<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'SettingTrait.php';

use Phinx\Db\Adapter\MysqlAdapter;

class V20210917093354 extends Phinx\Migration\AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->alterConnectTable();
        $this->handleLocalAuthSettings();
    }

    protected function alterConnectTable()
    {
        $table = $this->table('kg_connect');

        if (!$table->hasColumn('deleted')) {
            $table->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'provider',
            ]);
        }
        if (!$table->hasIndexByName('user_id')) {
            $table->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ]);
        }
        if (!$table->hasIndexByName('open_id')) {
            $table->addIndex(['open_id'], [
                'name' => 'open_id',
                'unique' => false,
            ]);
        }
        $table->save();
    }

    protected function handleLocalAuthSettings()
    {
        $rows = [
            [
                'section' => 'oauth.local',
                'item_key' => 'register_with_phone',
                'item_value' => '1',
            ],
            [
                'section' => 'oauth.local',
                'item_key' => 'register_with_email',
                'item_value' => '1',
            ]
        ];

        $this->insertSettings($rows);
    }

}
