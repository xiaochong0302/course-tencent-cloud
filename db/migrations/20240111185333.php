<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20240111185333 extends AbstractMigration
{

    public function up()
    {
        $this->alterChapterTable();
        $this->handleChapterUsers();
        $this->recountCourseResources();
    }

    protected function alterChapterTable()
    {
        $table = $this->table('kg_chapter');

        if ($table->hasColumn('resource_count')) {
            $table->removeColumn('resource_count');
        }

        if ($table->hasColumn('consult_count')) {
            $table->removeColumn('consult_count');
        }

        $table->save();
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

    protected function recountCourseResources()
    {
        $courses = $this->getQueryBuilder()
            ->select(['id'])
            ->from('kg_course')
            ->execute()->fetchAll(PDO::FETCH_ASSOC);

        if (count($courses) == 0) return;

        foreach ($courses as $course) {
            $resourceCount = $this->getQueryBuilder()
                ->select(['id'])
                ->from('kg_resource')
                ->where(['course_id' => $course['id']])
                ->execute()->count();

            $this->getQueryBuilder()
                ->update('kg_course')
                ->set('resource_count', $resourceCount)
                ->where(['id' => $course['id']])
                ->execute();
        }
    }

}
