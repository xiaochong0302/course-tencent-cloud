<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20221021035953 extends AbstractMigration
{

    public function up()
    {
        $this->alterPageTable();
        $this->alterHelpTable();
        $this->alterUserTable();
    }

    protected function alterPageTable()
    {
        $table = $this->table('kg_page');

        if (!$table->hasColumn('view_count')) {
            $table->addColumn('view_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '浏览数',
                'after' => 'deleted',
            ]);
        }

        $table->save();
    }

    protected function alterHelpTable()
    {
        $table = $this->table('kg_help');

        if (!$table->hasColumn('view_count')) {
            $table->addColumn('view_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '浏览数',
                'after' => 'deleted',
            ]);
        }

        $table->save();
    }

    protected function alterUserTable()
    {
        $table = $this->table('kg_user');

        if (!$table->hasColumn('notice_count')) {
            $table->addColumn('notice_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '通知数',
                'after' => 'favorite_count',
            ]);
        }

        $table->save();
    }

}
