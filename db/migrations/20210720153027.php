<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20210720153027 extends AbstractMigration
{

    public function up()
    {
        $this->createCourseTagTable();
        $this->alterCategoryTable();
        $this->alterCourseTable();
        $this->alterTagTable();
        $this->handleTagScopes();
        $this->handleCategoryIcon();
        $this->handleCourseTags();
        $this->handleReviewPublish();
        $this->handleConsultPublish();
        $this->handleRoleRoutes();
    }

    protected function createCourseTagTable()
    {
        $tableName = 'kg_course_tag';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'COMPACT',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('tag_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'tag_id',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['tag_id'], [
                'name' => 'tag_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function alterCourseTable()
    {
        $table = $this->table('kg_course');

        if (!$table->hasColumn('tags')) {
            $table->addColumn('tags', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标签',
                'after' => 'summary',
            ]);
        }

        $table->save();
    }

    protected function alterCategoryTable()
    {
        $table = $this->table('kg_category');

        if (!$table->hasColumn('alias')) {
            $table->addColumn('alias', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '别名',
                'after' => 'name',
            ]);
        }

        if (!$table->hasColumn('icon')) {
            $table->addColumn('icon', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '图标',
                'after' => 'name',
            ]);
        }

        $table->save();
    }

    protected function alterTagTable()
    {
        $table = $this->table('kg_tag');

        if (!$table->hasColumn('scopes')) {
            $table->addColumn('scopes', 'string', [
                'null' => false,
                'default' => 'all',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '范围',
                'after' => 'icon',
            ]);
        }

        if (!$table->hasColumn('course_count')) {
            $table->addColumn('course_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程数',
                'after' => 'follow_count',
            ]);
        }

        if (!$table->hasColumn('article_count')) {
            $table->addColumn('article_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文章数',
                'after' => 'course_count',
            ]);
        }

        if (!$table->hasColumn('question_count')) {
            $table->addColumn('question_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '问题数',
                'after' => 'article_count',
            ]);
        }

        $table->save();
    }

    protected function handleCourseTags()
    {
        $this->getQueryBuilder()
            ->update('kg_course')
            ->set('tags', '[]')
            ->execute();
    }

    protected function handleCategoryIcon()
    {
        $this->getQueryBuilder()
            ->update('kg_category')
            ->set('icon', '/img/default/user_avatar.png')
            ->execute();
    }

    protected function handleTagScopes()
    {
        $this->getQueryBuilder()
            ->update('kg_tag')
            ->set('scopes', 'all')
            ->execute();
    }

    protected function handleReviewPublish()
    {
        $this->getQueryBuilder()
            ->update('kg_review')
            ->set('published', 2)
            ->where(['published' => 1])
            ->execute();

        $this->getQueryBuilder()
            ->update('kg_review')
            ->set('published', 3)
            ->where(['published' => 0])
            ->execute();
    }

    protected function handleConsultPublish()
    {
        $this->getQueryBuilder()
            ->update('kg_review')
            ->set('published', 2)
            ->where(['published' => 1])
            ->execute();

        $this->getQueryBuilder()
            ->update('kg_review')
            ->set('published', 3)
            ->where(['published' => 0])
            ->execute();
    }

    protected function handleRoleRoutes()
    {
        $roles = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_role')
            ->execute()->fetchAll('assoc');

        foreach ($roles as $role) {
            $routes = str_replace(
                ['publish_review', 'report_review'],
                ['moderate', 'report'],
                $role['routes']
            );
            $this->getQueryBuilder()
                ->update('kg_role')
                ->set('routes', $routes)
                ->where(['id' => $role['id']])
                ->execute();
        }
    }

}
