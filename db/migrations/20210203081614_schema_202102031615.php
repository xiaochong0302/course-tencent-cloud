<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

class Schema202102031615 extends Phinx\Migration\AbstractMigration
{

    public function change()
    {
        $this->table('kg_course')
            ->addColumn('origin_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '原始价格',
                'after' => 'teacher_id',
            ])
            ->save();

        $this->updateOriginPrice();
    }

    protected function updateOriginPrice()
    {
        $this->execute("UPDATE kg_course SET origin_price = round(1.5 * market_price)");
    }

}
