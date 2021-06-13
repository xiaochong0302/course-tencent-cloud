<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202101261615 extends Phinx\Migration\AbstractMigration
{

    public function change()
    {
        $courseTable = $this->table('kg_course');

        if ($courseTable->hasColumn('resource_count') == false) {
            $courseTable->addColumn('resource_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '资源数',
                'after' => 'deleted',
            ])->save();
        }

        $chapterTable = $this->table('kg_chapter');

        if ($chapterTable->hasColumn('resource_count') == false) {
            $chapterTable->addColumn('resource_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '资源数',
                'after' => 'deleted',
            ])->save();
        }

        /**
         * 补救前期遗漏，重新统计数据
         */
        $this->recount();
    }

    protected function recount()
    {
        $resources = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_resource')
            ->execute();

        $courseMappings = [];
        $chapterMappings = [];

        if ($resources->count() > 0) {
            foreach ($resources as $resource) {
                $courseId = $resource['course_id'];
                $chapterId = $resource['chapter_id'];
                $courseMappings[$courseId] = isset($courseMappings[$courseId]) ? $courseMappings[$courseId] + 1 : 1;
                $chapterMappings[$chapterId] = isset($chapterMappings[$chapterId]) ? $chapterMappings[$chapterId] + 1 : 1;
            }
            $this->recountCourseResource($courseMappings);
            $this->recountChapterResource($chapterMappings);
        }
    }

    protected function recountCourseResource($mappings)
    {
        $builder = $this->getQueryBuilder();

        foreach ($mappings as $courseId => $resourceCount) {
            $builder->update('kg_course')
                ->set('resource_count', $resourceCount)
                ->where(['id' => $courseId])
                ->execute();
        }
    }

    protected function recountChapterResource($mappings)
    {
        $builder = $this->getQueryBuilder();

        foreach ($mappings as $chapterId => $resourceCount) {
            $builder->update('kg_chapter')
                ->set('resource_count', $resourceCount)
                ->where(['id' => $chapterId])
                ->execute();
        }
    }

}
