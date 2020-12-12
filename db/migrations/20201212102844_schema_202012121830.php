<?php

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202012121830 extends Phinx\Migration\AbstractMigration
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
            ->addColumn('union_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'union_id',
                'after' => 'user_id',
            ])
            ->changeColumn('open_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放ID',
                'after' => 'union_id',
            ])
            ->changeColumn('open_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放名称',
                'after' => 'open_id',
            ])
            ->changeColumn('open_avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放头像',
                'after' => 'open_name',
            ])
            ->changeColumn('provider', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '提供方',
                'after' => 'open_avatar',
            ])
            ->changeColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '删除标识',
                'after' => 'provider',
            ])
            ->changeColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->changeColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['union_id', 'provider'], [
                'name' => 'union_id_provider',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->save();
        $this->table('kg_wechat_subscribe', [
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
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('open_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放ID',
                'after' => 'user_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '删除标识',
                'after' => 'open_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }
}
