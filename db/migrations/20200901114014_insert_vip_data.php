<?php

use Phinx\Migration\AbstractMigration;

final class InsertVipData extends AbstractMigration
{

    public function up()
    {
        $now = time();

        $rows = [
            [
                'id' => 1,
                'title' => '1个月',
                'expiry' => 1,
                'price' => 60.00,
                'create_time' => $now,
            ],
            [
                'id' => 2,
                'title' => '3个月',
                'expiry' => 3,
                'price' => 150.00,
                'create_time' => $now,
            ],
            [
                'id' => 3,
                'title' => '6个月',
                'expiry' => 6,
                'price' => 240.00,
                'create_time' => $now,
            ],
            [
                'id' => 4,
                'title' => '12个月',
                'expiry' => 12,
                'price' => 360.00,
                'create_time' => $now,
            ],
        ];

        $this->table('kg_vip')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_vip');
    }

}
