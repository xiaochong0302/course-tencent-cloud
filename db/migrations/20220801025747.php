<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20220801025747 extends AbstractMigration
{

    public function up()
    {
        $this->createMigrationTaskTable();
        $this->dropImTables();
        $this->deleteImGroupNav();
        $this->deleteImSettings();
    }

    protected function createMigrationTaskTable()
    {
        $tableName = 'kg_migration_task';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('version', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '版本',
                'after' => 'id',
            ])
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'version',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addIndex(['version'], [
                'name' => 'version',
                'unique' => true,
            ])
            ->create();
    }

    protected function dropImTables()
    {
        $tableNames = [
            'kg_im_friend_group',
            'kg_im_friend_user',
            'kg_im_group',
            'kg_im_group_user',
            'kg_im_message',
            'kg_im_notice',
            'kg_im_user',
        ];

        foreach ($tableNames as $tableName) {
            if ($this->table($tableName)->exists()) {
                $this->table($tableName)->drop()->save();
            }
        }
    }

    protected function deleteImGroupNav()
    {
        $this->getQueryBuilder()
            ->delete('kg_nav')
            ->where(['url' => '/im/group/list'])
            ->execute();
    }

    protected function deleteImSettings()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->whereInList('section', ['im.main', 'im.cs'])
            ->execute();
    }

}
