<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20240608110418 extends AbstractMigration
{

    public function up()
    {
        $this->dropRewardTable();
    }

    protected function dropRewardTable()
    {
        $table = $this->table('kg_reward');

        if ($table->exists()) {
            $table->drop()->save();
        }
    }

}
