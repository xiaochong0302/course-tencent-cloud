<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

class Schema202101051030 extends Phinx\Migration\AbstractMigration
{

    public function change()
    {
        $this->table('kg_task')
            ->addIndex(['create_time'], [
                'name' => 'create_time',
                'unique' => false,
            ])
            ->save();
    }

}
