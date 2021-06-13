<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20210602034627 extends AbstractMigration
{

    public function up()
    {
        $this->modifyUserTable();
    }

    public function down()
    {
        $this->table('kg_user')
            ->removeColumn('comment_count')
            ->save();
    }

    protected function modifyUserTable()
    {
        $this->table('kg_user')
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数量',
                'after' => 'answer_count',
            ])->save();
    }

}
