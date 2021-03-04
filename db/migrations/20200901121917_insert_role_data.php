<?php

use Phinx\Migration\AbstractMigration;

final class InsertRoleData extends AbstractMigration
{

    public function up()
    {
        $now = time();

        $rows = [
            [
                'id' => 1,
                'type' => 1,
                'name' => '管理员',
                'summary' => '管理员',
                'routes' => '',
                'user_count' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 2,
                'type' => 1,
                'name' => '运营',
                'summary' => '运营人员',
                'routes' => '',
                'user_count' => 0,
                'create_time' => $now,
            ],
            [
                'id' => 3,
                'type' => 1,
                'name' => '编辑',
                'summary' => '编辑人员',
                'routes' => '',
                'user_count' => 0,
                'create_time' => $now,
            ],
            [
                'id' => 4,
                'type' => 1,
                'name' => '财务',
                'summary' => '财务人员',
                'routes' => '',
                'user_count' => 0,
                'create_time' => $now,
            ],
        ];

        $this->table('kg_role')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_role');
    }

}
