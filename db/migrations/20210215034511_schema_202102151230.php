<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202102151230 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $this->table('kg_task')
            ->addColumn('max_try_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '最大尝试数',
                'after' => 'try_count',
            ])->save();
    }

    public function down()
    {
        $this->table('kg_task')
            ->removeColumn('max_try_count')
            ->save();
    }

}
