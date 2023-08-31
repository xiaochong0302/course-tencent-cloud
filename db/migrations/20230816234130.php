<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20230816234130 extends AbstractMigration
{

    public function up()
    {
        $this->alterReviewLikeTable();
    }

    protected function alterReviewLikeTable()
    {
        $table = $this->table('kg_review_like');

        if (!$table->hasColumn('deleted')) {
            $table->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ]);
        }

        $table->save();
    }

}
