<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20230910174508 extends AbstractMigration
{

    public function up()
    {
        $this->alterCourseTable();
    }

    protected function alterCourseTable()
    {
        $table = $this->table('kg_course');

        if (!$table->hasIndexByName('category_id')) {
            $table->addIndex(['category_id'], [
                'name' => 'category_id',
                'unique' => false,
            ]);
        }

        if (!$table->hasIndexByName('teacher_id')) {
            $table->addIndex(['teacher_id'], [
                'name' => 'teacher_id',
                'unique' => false,
            ]);
        }

        $table->save();
    }

}
