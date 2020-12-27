<?php

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
                'comment' => '推荐标识',
                'after' => 'attrs',
            ])
            ->save();
    }

}
