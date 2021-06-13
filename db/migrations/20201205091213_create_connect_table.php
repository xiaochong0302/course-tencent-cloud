<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;

class CreateConnectTable extends Phinx\Migration\AbstractMigration
{

    public function change()
    {
        $this->table('kg_connect', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('open_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放ID',
                'after' => 'user_id',
            ])
            ->addColumn('open_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放名称',
                'after' => 'open_id',
            ])
            ->addColumn('open_avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放头像',
                'after' => 'open_name',
            ])
            ->addColumn('provider', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '提供方',
                'after' => 'open_avatar',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'provider',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['open_id', 'provider'], [
                'name' => 'open_provider',
                'unique' => false,
            ])
            ->create();
    }

}
