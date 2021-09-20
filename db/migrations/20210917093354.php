<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;

class V20210917093354 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $this->alterConnectTable();
        $this->alterWechatSubscribeTable();
    }

    protected function alterConnectTable()
    {
        $this->table('kg_connect')
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'provider',
            ])
            ->save();
    }

    protected function alterWechatSubscribeTable()
    {
        $this->table('kg_wechat_subscribe')
            ->addColumn('union_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '联合ID',
                'after' => 'open_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'open_id',
            ])
            ->save();
    }

}
