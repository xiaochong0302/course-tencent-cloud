<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202012271615 extends Phinx\Migration\AbstractMigration
{

    public function change()
    {
        $this->table('kg_course')
            ->addColumn('featured', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '推荐标识',
                'after' => 'attrs',
            ])
            ->save();
    }

}
