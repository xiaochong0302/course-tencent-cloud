<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20211017085325 extends AbstractMigration
{

    public function up()
    {
        $this->alterCourseTable();
    }

    protected function alterCourseTable()
    {
        $table = $this->table('kg_course');

        if (!$table->hasColumn('fake_user_count')) {
            $table->addColumn('fake_user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '伪造用户数',
                'after' => 'user_count',
            ]);
        }

        $table->save();
    }

}
