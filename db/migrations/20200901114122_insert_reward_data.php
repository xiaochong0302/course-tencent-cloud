<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class InsertRewardData extends AbstractMigration
{

    public function up()
    {
        $now = time();

        $rows = [
            [
                'id' => 1,
                'title' => '2元',
                'price' => 2.00,
                'create_time' => $now,
            ],
            [
                'id' => 2,
                'title' => '5元',
                'price' => 5.00,
                'create_time' => $now,
            ],
            [
                'id' => 3,
                'title' => '10元',
                'price' => 10.00,
                'create_time' => $now,
            ],
            [
                'id' => 4,
                'title' => '20元',
                'price' => 20.00,
                'create_time' => $now,
            ],
            [
                'id' => 5,
                'title' => '50元',
                'price' => 50.00,
                'create_time' => $now,
            ],
            [
                'id' => 6,
                'title' => '100元',
                'price' => 100.00,
                'create_time' => $now,
            ],
        ];

        $this->table('kg_reward')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_reward');
    }

}
