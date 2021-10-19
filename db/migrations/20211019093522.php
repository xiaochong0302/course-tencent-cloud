<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20211019093522 extends AbstractMigration
{

    public function up()
    {
        $this->alterUserSessionTable();
        $this->alterUserTokenTable();
    }

    protected function alterUserSessionTable()
    {
        $this->table('kg_user_session')
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'client_ip',
            ])->save();
    }

    protected function alterUserTokenTable()
    {
        $this->table('kg_user_token')
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'client_ip',
            ])->save();
    }

}
