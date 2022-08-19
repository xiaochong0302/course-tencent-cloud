<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20210825111618 extends AbstractMigration
{

    public function up()
    {
        $this->alterUploadTable();
    }

    protected function alterUploadTable()
    {
        $table = $this->table('kg_upload');

        if ($table->hasIndexByName('md5')) {
            $table->removeIndexByName('md5')->save();
            $table->addIndex('md5')->save();
        }

    }

}
