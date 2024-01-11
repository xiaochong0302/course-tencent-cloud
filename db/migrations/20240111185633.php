<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20240111185633 extends AbstractMigration
{

    public function up()
    {
        $this->handleChapterUsers();
    }

    /**
     * 纠正 chapter_user 表中 plan_id = 0 的数据
     *
     * @return void
     */
    protected function handleChapterUsers()
    {
        $sql = 'UPDATE kg_chapter_user AS a JOIN kg_course_user AS b 
                ON a.course_id = b.course_id AND a.user_id = b.user_id 
                SET a.plan_id = b.plan_id WHERE a.plan_id = 0';

        $this->query($sql);
    }

}
