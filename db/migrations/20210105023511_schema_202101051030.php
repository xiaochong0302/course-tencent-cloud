<?php

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
