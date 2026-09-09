<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20260905064239 extends AbstractMigration
{

    public function up(): void
    {
        $this->createAccountTable();
        $this->createAuditTable();
        $this->createCategoryTable();
        $this->createChapterTable();
        $this->createChapterLikeTable();
        $this->createChapterReadTable();
        $this->createChapterUserTable();
        $this->createChapterVodTable();
        $this->createCourseTable();
        $this->createCourseFavoriteTable();
        $this->createCourseRatingTable();
        $this->createCourseRelatedTable();
        $this->createCourseUserTable();
        $this->createLearningTable();
        $this->createMigrationPhalconTable();
        $this->createNavTable();
        $this->createOnlineTable();
        $this->createOrderTable();
        $this->createOrderStatusTable();
        $this->createPageTable();
        $this->createRefundTable();
        $this->createRefundStatusTable();
        $this->createReviewTable();
        $this->createReviewLikeTable();
        $this->createRoleTable();
        $this->createSettingTable();
        $this->createSlideTable();
        $this->createTaskTable();
        $this->createTradeTable();
        $this->createTradeStatusTable();
        $this->createUploadTable();
        $this->createUserTable();
        $this->createUserBalanceTable();
        $this->createUserSessionTable();
        $this->createUserTokenTable();
        $this->createVipTable();
    }

    protected function createAccountTable(): void
    {
        $tableName = 'kg_account';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('email', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '邮箱',
                'after' => 'id',
            ])
            ->addColumn('phone', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '手机',
                'after' => 'email',
            ])
            ->addColumn('password', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => '密码',
                'after' => 'phone',
            ])
            ->addColumn('salt', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => '密盐',
                'after' => 'password',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'salt',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['phone'], [
                'name' => 'phone',
                'unique' => false,
            ])
            ->addIndex(['email'], [
                'name' => 'email',
                'unique' => false,
            ])
            ->create();
    }

    protected function createAuditTable(): void
    {
        $tableName = 'kg_audit';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('user_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '用户名称',
                'after' => 'user_id',
            ])
            ->addColumn('user_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '用户IP',
                'after' => 'user_name',
            ])
            ->addColumn('req_route', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'comment' => '请求路由',
                'after' => 'user_ip',
            ])
            ->addColumn('req_path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '请求路径',
                'after' => 'req_route',
            ])
            ->addColumn('req_data', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '请求数据',
                'after' => 'req_path',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'req_data',
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCategoryTable(): void
    {
        $tableName = 'kg_category';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'id',
            ])
            ->addColumn('level', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '层级',
                'after' => 'parent_id',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '类型',
                'after' => 'level',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '名称',
                'after' => 'type',
            ])
            ->addColumn('icon', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '图标',
                'after' => 'name',
            ])
            ->addColumn('alias', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '别名',
                'after' => 'icon',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '路径',
                'after' => 'alias',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'path',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'priority',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('child_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '节点数',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'child_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createChapterTable(): void
    {
        $tableName = 'kg_chapter';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'id',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'parent_id',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '标题',
                'after' => 'course_id',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '简介',
                'after' => 'title',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '关键字',
                'after' => 'summary',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'keywords',
            ])
            ->addColumn('free', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '免费标识',
                'after' => 'priority',
            ])
            ->addColumn('model', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '模式类型',
                'after' => 'free',
            ])
            ->addColumn('attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '扩展属性',
                'after' => 'model',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'attrs',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('lesson_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课时数',
                'after' => 'deleted',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学员数',
                'after' => 'lesson_count',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数量',
                'after' => 'user_count',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'comment_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'like_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['parent_id'], [
                'name' => 'parent_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterLikeTable(): void
    {
        $tableName = 'kg_chapter_like';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterReadTable(): void
    {
        $tableName = 'kg_chapter_read';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '内容',
                'after' => 'chapter_id',
            ])
            ->addColumn('settings', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'comment' => '图文设置',
                'after' => 'content',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'settings',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterUserTable(): void
    {
        $tableName = 'kg_chapter_user';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('plan_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '计划编号',
                'after' => 'user_id',
            ])
            ->addColumn('duration', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学习时长',
                'after' => 'plan_id',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '播放位置',
                'after' => 'duration',
            ])
            ->addColumn('progress', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学习进度',
                'after' => 'position',
            ])
            ->addColumn('consumed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '消费标识',
                'after' => 'progress',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'consumed',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createChapterVodTable(): void
    {
        $tableName = 'kg_chapter_vod';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('file_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => '文件编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('file_origin', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '原始信息',
                'after' => 'file_id',
            ])
            ->addColumn('file_transcode', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'comment' => '转码信息',
                'after' => 'file_origin',
            ])
            ->addColumn('file_encrypt', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'comment' => '加密信息',
                'after' => 'file_transcode',
            ])
            ->addColumn('file_remote', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'comment' => '远程信息',
                'after' => 'file_encrypt',
            ])
            ->addColumn('settings', 'string', [
                'null' => false,
                'default' => '[]',
                'limit' => 1000,
                'comment' => '点播设置',
                'after' => 'file_remote',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'settings',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['file_id'], [
                'name' => 'file_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseTable(): void
    {
        $tableName = 'kg_course';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '封面',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('tags', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '标签',
                'after' => 'summary',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '关键字',
                'after' => 'tags',
            ])
            ->addColumn('details', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '详情',
                'after' => 'keywords',
            ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '分类编号',
                'after' => 'details',
            ])
            ->addColumn('teacher_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '讲师编号',
                'after' => 'category_id',
            ])
            ->addColumn('market_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '市场价格',
                'after' => 'teacher_id',
            ])
            ->addColumn('vip_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '会员价格',
                'after' => 'market_price',
            ])
            ->addColumn('study_expiry', 'integer', [
                'null' => false,
                'default' => '12',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学习期限',
                'after' => 'vip_price',
            ])
            ->addColumn('refund_expiry', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '退款期限',
                'after' => 'study_expiry',
            ])
            ->addColumn('rating', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '用户评分',
                'after' => 'refund_expiry',
            ])
            ->addColumn('score', 'float', [
                'null' => false,
                'default' => '0.00',
                'comment' => '综合得分',
                'after' => 'rating',
            ])
            ->addColumn('model', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '模型',
                'after' => 'score',
            ])
            ->addColumn('level', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '难度',
                'after' => 'model',
            ])
            ->addColumn('attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '扩展属性',
                'after' => 'level',
            ])
            ->addColumn('featured', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '推荐标识',
                'after' => 'attrs',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'featured',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('resource_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '资料数',
                'after' => 'deleted',
            ])
            ->addColumn('paper_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '考试数',
                'after' => 'resource_count',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学员数',
                'after' => 'paper_count',
            ])
            ->addColumn('fake_user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '伪造用户数',
                'after' => 'user_count',
            ])
            ->addColumn('lesson_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课时数',
                'after' => 'fake_user_count',
            ])
            ->addColumn('package_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '套餐数',
                'after' => 'lesson_count',
            ])
            ->addColumn('review_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评价数',
                'after' => 'package_count',
            ])
            ->addColumn('consult_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '咨询数',
                'after' => 'review_count',
            ])
            ->addColumn('favorite_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '收藏数',
                'after' => 'consult_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'favorite_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['category_id'], [
                'name' => 'category_id',
                'unique' => false,
            ])
            ->addIndex(['teacher_id'], [
                'name' => 'teacher_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseFavoriteTable(): void
    {
        $tableName = 'kg_course_favorite';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'course_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseRatingTable(): void
    {
        $tableName = 'kg_course_rating';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['course_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '主键编号',
            ])
            ->addColumn('rating', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '综合评分',
                'after' => 'course_id',
            ])
            ->addColumn('rating1', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '维度1评分',
                'after' => 'rating',
            ])
            ->addColumn('rating2', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '维度2评分',
                'after' => 'rating1',
            ])
            ->addColumn('rating3', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '维度3评分',
                'after' => 'rating2',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'rating3',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createCourseRelatedTable(): void
    {
        $tableName = 'kg_course_related';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('related_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '相关编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'related_id',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createCourseUserTable(): void
    {
        $tableName = 'kg_course_user';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'course_id',
            ])
            ->addColumn('plan_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '计划编号',
                'after' => 'user_id',
            ])
            ->addColumn('source_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '来源类型',
                'after' => 'plan_id',
            ])
            ->addColumn('duration', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学习时长',
                'after' => 'source_type',
            ])
            ->addColumn('progress', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学习进度',
                'after' => 'duration',
            ])
            ->addColumn('reviewed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评价标识',
                'after' => 'progress',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'reviewed',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'deleted',
            ])
            ->addColumn('expiry_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'active_time',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'expiry_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createLearningTable(): void
    {
        $tableName = 'kg_learning';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('request_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '请求编号',
                'after' => 'id',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'request_id',
            ])
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课时编号',
                'after' => 'course_id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('plan_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '计划编号',
                'after' => 'user_id',
            ])
            ->addColumn('duration', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学习时长',
                'after' => 'plan_id',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '播放位置',
                'after' => 'duration',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'position',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'client_ip',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['request_id'], [
                'name' => 'request_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createMigrationPhalconTable(): void
    {
        $tableName = 'kg_migration_phalcon';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('version', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '版本',
                'after' => 'id',
            ])
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'version',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addIndex(['version'], [
                'name' => 'name',
                'unique' => true,
            ])
            ->create();
    }

    protected function createNavTable(): void
    {
        $tableName = 'kg_nav';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'id',
            ])
            ->addColumn('level', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '层级',
                'after' => 'parent_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '名称',
                'after' => 'level',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '路径',
                'after' => 'name',
            ])
            ->addColumn('target', 'string', [
                'null' => false,
                'default' => '_blank',
                'limit' => 30,
                'comment' => '打开方式',
                'after' => 'path',
            ])
            ->addColumn('url', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '链接地址',
                'after' => 'target',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '位置',
                'after' => 'url',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'position',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'priority',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('child_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '子类数量',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'child_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createOnlineTable(): void
    {
        $tableName = 'kg_online';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'user_id',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'client_ip',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->addIndex(['active_time'], [
                'name' => 'active_time',
                'unique' => false,
            ])
            ->create();
    }

    protected function createOrderTable(): void
    {
        $tableName = 'kg_order';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '订单标题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '订单金额',
                'after' => 'subject',
            ])
            ->addColumn('channel', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '平台类型',
                'after' => 'amount',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'channel',
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '条目编号',
                'after' => 'owner_id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '条目类型',
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 3000,
                'comment' => '条目内容',
                'after' => 'item_type',
            ])
            ->addColumn('promotion_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '促销编号',
                'after' => 'item_info',
            ])
            ->addColumn('promotion_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '促销类型',
                'after' => 'promotion_id',
            ])
            ->addColumn('promotion_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '促销信息',
                'after' => 'promotion_type',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'promotion_info',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态标识',
                'after' => 'client_ip',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'status',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->addIndex(['item_id', 'item_type'], [
                'name' => 'item',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['create_time'], [
                'name' => 'create_time',
                'unique' => false,
            ])
            ->create();
    }

    protected function createOrderStatusTable(): void
    {
        $tableName = 'kg_order_status';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('order_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单状态',
                'after' => 'order_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createPageTable(): void
    {
        $tableName = 'kg_page';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('alias', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'comment' => '别名',
                'after' => 'title',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '关键字',
                'after' => 'alias',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '摘要',
                'after' => 'keywords',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '内容',
                'after' => 'summary',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'content',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('view_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '浏览数',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'view_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createRefundTable(): void
    {
        $tableName = 'kg_refund';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('order_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'owner_id',
            ])
            ->addColumn('trade_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '交易编号',
                'after' => 'order_id',
            ])
            ->addColumn('sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => '退款序号',
                'after' => 'trade_id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '退款主题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '退款金额',
                'after' => 'subject',
            ])
            ->addColumn('channel', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '平台类型',
                'after' => 'amount',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态类型',
                'after' => 'channel',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'status',
            ])
            ->addColumn('apply_note', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '申请备注',
                'after' => 'deleted',
            ])
            ->addColumn('review_note', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '审核备注',
                'after' => 'apply_note',
            ])
            ->addColumn('error_note', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '错误备注',
                'after' => 'review_note',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'error_note',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->addIndex(['trade_id'], [
                'name' => 'trade_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createRefundStatusTable(): void
    {
        $tableName = 'kg_refund_status';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('refund_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单状态',
                'after' => 'refund_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->addIndex(['refund_id'], [
                'name' => 'refund_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createReviewTable(): void
    {
        $tableName = 'kg_review';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
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
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'course_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'owner_id',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '内容',
                'after' => 'client_ip',
            ])
            ->addColumn('reply', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '回复',
                'after' => 'content',
            ])
            ->addColumn('rating', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '综合评分',
                'after' => 'reply',
            ])
            ->addColumn('rating1', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '维度1评分',
                'after' => 'rating',
            ])
            ->addColumn('rating2', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '维度2评分',
                'after' => 'rating1',
            ])
            ->addColumn('rating3', 'float', [
                'null' => false,
                'default' => '5.00',
                'comment' => '维度3评分',
                'after' => 'rating2',
            ])
            ->addColumn('anonymous', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '匿名标识',
                'after' => 'rating3',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'anonymous',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'deleted',
            ])
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'like_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'report_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createReviewLikeTable(): void
    {
        $tableName = 'kg_review_like';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('review_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评价编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'review_id',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['review_id'], [
                'name' => 'review_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createRoleTable(): void
    {
        $tableName = 'kg_role';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '2',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '类型',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '名称',
                'after' => 'type',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '简介',
                'after' => 'name',
            ])
            ->addColumn('routes', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '权限路由',
                'after' => 'summary',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'routes',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '成员数量',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'user_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createSettingTable(): void
    {
        $tableName = 'kg_setting';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('section', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'comment' => '配置组',
                'after' => 'id',
            ])
            ->addColumn('item_key', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'comment' => '配置项',
                'after' => 'section',
            ])
            ->addColumn('item_value', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '配置值',
                'after' => 'item_key',
            ])
            ->addIndex(['section', 'item_key'], [
                'name' => 'section_key',
                'unique' => true,
            ])
            ->create();
    }

    protected function createSlideTable(): void
    {
        $tableName = 'kg_slide';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '封面',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('platform', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '平台类型',
                'after' => 'summary',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '10',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'platform',
            ])
            ->addColumn('target_id', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '目标编号',
                'after' => 'priority',
            ])
            ->addColumn('target_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '目标类型',
                'after' => 'target_id',
            ])
            ->addColumn('target_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'comment' => '目标属性',
                'after' => 'target_type',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布状态',
                'after' => 'target_info',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createTaskTable(): void
    {
        $tableName = 'kg_task';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 3000,
                'after' => 'item_type',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'item_info',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'status',
            ])
            ->addColumn('try_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'priority',
            ])
            ->addColumn('max_try_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'try_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'max_try_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createTradeTable(): void
    {
        $tableName = 'kg_trade';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('order_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'owner_id',
            ])
            ->addColumn('sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => '交易序号',
                'after' => 'order_id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '交易主题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '交易金额',
                'after' => 'subject',
            ])
            ->addColumn('channel', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '平台类型',
                'after' => 'amount',
            ])
            ->addColumn('channel_sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '平台序号',
                'after' => 'channel',
            ])
            ->addColumn('channel_identity', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '买家标识',
                'after' => 'channel_sn',
            ])
            ->addColumn('scene', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '场景类型',
                'after' => 'channel_identity',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态类型',
                'after' => 'scene',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'status',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createTradeStatusTable(): void
    {
        $tableName = 'kg_trade_status';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('trade_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '订单状态',
                'after' => 'trade_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->addIndex(['trade_id'], [
                'name' => 'trade_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createUploadTable(): void
    {
        $tableName = 'kg_upload';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '条目类型',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '文件名',
                'after' => 'type',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '路径',
                'after' => 'name',
            ])
            ->addColumn('mime', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => 'mime',
                'after' => 'path',
            ])
            ->addColumn('md5', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'comment' => 'md5',
                'after' => 'mime',
            ])
            ->addColumn('size', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '大小',
                'after' => 'md5',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'size',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['md5'], [
                'name' => 'md5',
                'unique' => false,
            ])
            ->create();
    }

    protected function createUserTable(): void
    {
        $tableName = 'kg_user';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '主键编号',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '名称',
                'after' => 'id',
            ])
            ->addColumn('avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '头像',
                'after' => 'name',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '头衔',
                'after' => 'avatar',
            ])
            ->addColumn('about', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'comment' => '简介',
                'after' => 'title',
            ])
            ->addColumn('profile', 'text', [
                'null' => false,
                'limit' => 65535,
                'comment' => '个人资料',
                'after' => 'about',
            ])
            ->addColumn('area', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '地区',
                'after' => 'profile',
            ])
            ->addColumn('gender', 'integer', [
                'null' => false,
                'default' => '3',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '性别',
                'after' => 'area',
            ])
            ->addColumn('vip', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '会员标识',
                'after' => 'gender',
            ])
            ->addColumn('locked', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '锁定标识',
                'after' => 'vip',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'locked',
            ])
            ->addColumn('edu_role', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '教学角色',
                'after' => 'deleted',
            ])
            ->addColumn('admin_role', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '后台角色',
                'after' => 'edu_role',
            ])
            ->addColumn('study_course_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程数',
                'after' => 'admin_role',
            ])
            ->addColumn('study_paper_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '考试数',
                'after' => 'study_course_count',
            ])
            ->addColumn('study_article_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文章数量',
                'after' => 'study_paper_count',
            ])
            ->addColumn('question_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '提问数',
                'after' => 'study_article_count',
            ])
            ->addColumn('answer_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '回答数',
                'after' => 'question_count',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数量',
                'after' => 'answer_count',
            ])
            ->addColumn('favorite_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '收藏数',
                'after' => 'comment_count',
            ])
            ->addColumn('notice_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '通知数',
                'after' => 'favorite_count',
            ])
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'notice_count',
            ])
            ->addColumn('vip_expiry_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '会员期限',
                'after' => 'report_count',
            ])
            ->addColumn('lock_expiry_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '锁定期限',
                'after' => 'vip_expiry_time',
            ])
            ->addColumn('active_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '活跃时间',
                'after' => 'lock_expiry_time',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'active_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['name'], [
                'name' => 'name',
                'unique' => false,
            ])
            ->create();
    }

    protected function createUserBalanceTable(): void
    {
        $tableName = 'kg_user_balance';

        if ($this->table($tableName)->exists()) {
            return;
        }

        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['user_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
            ])
            ->addColumn('cash', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '可用金额',
                'after' => 'user_id',
            ])
            ->addColumn('invoice', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '可用开票',
                'after' => 'cash',
            ])
            ->addColumn('point', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '可用积分',
                'after' => 'invoice',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'point',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

    protected function createUserSessionTable(): void
    {
        $tableName = 'kg_user_session';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('session_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '会话编号',
                'after' => 'user_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '终端类型',
                'after' => 'session_id',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'client_ip',
            ])
            ->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '创建时间',
                'after' => 'expire_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createUserTokenTable(): void
    {
        $tableName = 'kg_user_token';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('token', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '身份令牌',
                'after' => 'user_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '终端类型',
                'after' => 'token',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'client_ip',
            ])
            ->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '创建时间',
                'after' => 'expire_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createVipTable(): void
    {
        $tableName = 'kg_vip';

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
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => true,
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'comment' => '封面',
                'after' => 'title',
            ])
            ->addColumn('expiry', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '有效期',
                'after' => 'cover',
            ])
            ->addColumn('price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => 10,
                'scale' => 2,
                'comment' => '价格',
                'after' => 'expiry',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'price',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->create();
    }

}
