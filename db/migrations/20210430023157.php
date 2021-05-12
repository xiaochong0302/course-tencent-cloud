<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20210430023157 extends AbstractMigration
{

    public function up()
    {
        $this->createQuestionTable();
        $this->createQuestionTagTable();
        $this->createQuestionFavoriteTable();
        $this->createQuestionLikeTable();
        $this->createAnswerTable();
        $this->createAnswerLikeTable();
        $this->createReportTable();
        $this->modifyUserTable();
        $this->modifyArticleTable();
        $this->modifyConsultTable();
        $this->modifyReviewTable();
        $this->modifyCommentTable();
        $this->handleRoleRoutes();
        $this->handleQuestionNav();
        $this->handleArticleCover();
        $this->handleArticleClosed();
    }

    public function down()
    {
        $this->table('kg_question')->drop()->save();
        $this->table('kg_question_favorite')->drop()->save();
        $this->table('kg_question_tag')->drop()->save();
        $this->table('kg_question_like')->drop()->save();
        $this->table('kg_answer')->drop()->save();
        $this->table('kg_answer_like')->drop()->save();
        $this->table('kg_report')->drop()->save();

        $this->table('kg_user')
            ->removeColumn('question_count')
            ->removeColumn('answer_count')
            ->removeColumn('report_count')
            ->save();

        $this->table('kg_article')
            ->removeColumn('report_count')
            ->save();

        $this->table('kg_consult')
            ->removeColumn('report_count')
            ->save();

        $this->table('kg_review')
            ->removeColumn('report_count')
            ->save();

        $this->table('kg_comment')
            ->removeColumn('report_count')
            ->save();
    }

    protected function createQuestionTable()
    {
        $this->table('kg_question', [
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
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '分类编号',
                'after' => 'id',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'category_id',
            ])
            ->addColumn('last_replier_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '最后回应用户',
                'after' => 'owner_id',
            ])
            ->addColumn('last_answer_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '最后答案编号',
                'after' => 'last_replier_id',
            ])
            ->addColumn('accept_answer_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '采纳答案编号',
                'after' => 'last_answer_id',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'accept_answer_id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'title',
            ])
            ->addColumn('tags', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标签',
                'after' => 'cover',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '概要',
                'after' => 'tags',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'summary',
            ])
            ->addColumn('score', 'float', [
                'null' => false,
                'default' => '0.00',
                'comment' => '综合得分',
                'after' => 'content',
            ])
            ->addColumn('bounty', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '悬赏积分',
                'after' => 'score',
            ])
            ->addColumn('anonymous', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '匿名标识',
                'after' => 'bounty',
            ])
            ->addColumn('solved', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '解决标识',
                'after' => 'anonymous',
            ])
            ->addColumn('closed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '关闭标识',
                'after' => 'solved',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'closed',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'deleted',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('view_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '浏览数',
                'after' => 'client_ip',
            ])
            ->addColumn('answer_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '答案数',
                'after' => 'view_count',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数',
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
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'favorite_count',
            ])
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'like_count',
            ])
            ->addColumn('last_reply_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '回应时间',
                'after' => 'report_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'reply_time',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['last_reply_time'], [
                'name' => 'last_reply_time',
                'unique' => false,
            ])
            ->addIndex(['category_id'], [
                'name' => 'category_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createQuestionTagTable()
    {
        $this->table('kg_question_tag', [
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
            ->addColumn('question_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '问题编号',
                'after' => 'id',
            ])
            ->addColumn('tag_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'question_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'tag_id',
            ])
            ->addIndex(['tag_id'], [
                'name' => 'tag_id',
                'unique' => false,
            ])
            ->addIndex(['question_id'], [
                'name' => 'question_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createQuestionFavoriteTable()
    {
        $this->table('kg_question_favorite', [
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
            ->addColumn('question_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '问题编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'question_id',
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
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->addIndex(['question_id'], [
                'name' => 'question_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createQuestionLikeTable()
    {
        $this->table('kg_question_like', [
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
            ->addColumn('question_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '问题编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'question_id',
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
            ->addIndex(['question_id', 'user_id'], [
                'name' => 'question_user',
                'unique' => false,
            ])
            ->create();
    }

    protected function createAnswerTable()
    {
        $this->table('kg_answer', [
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
                'identity' => 'enable',
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
            ->addColumn('question_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '问题编号',
                'after' => 'owner_id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'question_id',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '概要',
                'after' => 'cover',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'summary',
            ])
            ->addColumn('anonymous', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '匿名标识',
                'after' => 'content',
            ])
            ->addColumn('accepted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '采纳标识',
                'after' => 'anonymous',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'accepted',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'deleted',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数',
                'after' => 'client_ip',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'comment_count',
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
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['question_id'], [
                'name' => 'question_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function createAnswerLikeTable()
    {
        $this->table('kg_answer_like', [
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
            ->addColumn('answer_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '答案编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'answer_id',
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
            ->addIndex(['answer_id', 'user_id'], [
                'name' => 'answer_user',
                'unique' => false,
            ])
            ->create();
    }

    protected function createReportTable()
    {
        $this->table('kg_report', [
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
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('reason', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '理由',
                'after' => 'id',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'reason',
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
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'item_type',
            ])
            ->addColumn('client_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('reviewed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '处理标识',
                'after' => 'client_ip',
            ])
            ->addColumn('accepted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '采纳标识',
                'after' => 'reviewed',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'accepted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['item_id', 'item_type'], [
                'name' => 'item',
                'unique' => false,
            ])
            ->create();
    }

    protected function modifyUserTable()
    {
        $this->table('kg_user')
            ->addColumn('question_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '提问数',
                'after' => 'article_count',
            ])
            ->addColumn('answer_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '回答数',
                'after' => 'question_count',
            ])->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'favorite_count',
            ])->save();
    }

    protected function modifyArticleTable()
    {
        $this->table('kg_article')
            ->renameColumn('allow_comment', 'closed')
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'like_count',
            ])->save();
    }

    protected function modifyConsultTable()
    {
        $this->table('kg_consult')
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'like_count',
            ])->save();
    }

    protected function modifyReviewTable()
    {
        $this->table('kg_review')
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'like_count',
            ])->save();
    }

    protected function modifyCommentTable()
    {
        $this->table('kg_comment')
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'like_count',
            ])->save();
    }

    protected function handleRoleRoutes()
    {
        $this->getQueryBuilder()
            ->update('kg_role')
            ->set('routes', '[]')
            ->where(['routes' => ''])
            ->execute();
    }

    protected function handleArticleCover()
    {
        $this->getQueryBuilder()
            ->update('kg_article')
            ->set('cover', '')
            ->where(['cover' => '/img/default/article_cover.png'])
            ->execute();
    }

    protected function handleArticleClosed()
    {
        $this->getQueryBuilder()
            ->update('kg_article')
            ->set('closed', 0)
            ->where(['closed' => 1])
            ->execute();
    }

    protected function handleQuestionNav()
    {
        $data = [
            'parent_id' => 0,
            'level' => 1,
            'name' => '问答',
            'target' => '_self',
            'url' => '/question/list',
            'position' => 1,
            'priority' => 99,
            'published' => 1,
            'create_time' => time(),
        ];

        $this->table('kg_nav')
            ->insert($data)
            ->save();

        $nav = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_nav')
            ->orderDesc('id')
            ->execute()->fetch('assoc');

        $this->getQueryBuilder()
            ->update('kg_nav')
            ->set('path', ",{$nav['id']},")
            ->where(['id' => $nav['id']])
            ->execute();
    }

}
