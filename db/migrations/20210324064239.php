<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20210324064239 extends AbstractMigration
{

    public function change()
    {
        $this->table('kg_account', [
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
            ->addColumn('email', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '邮箱',
                'after' => 'id',
            ])
            ->addColumn('phone', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '手机',
                'after' => 'email',
            ])
            ->addColumn('password', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '密码',
                'after' => 'phone',
            ])
            ->addColumn('salt', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
            ->addIndex(['email'], [
                'name' => 'email',
                'unique' => false,
            ])
            ->addIndex(['phone'], [
                'name' => 'phone',
                'unique' => false,
            ])
            ->create();
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
        $this->table('kg_area', [
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
                'comment' => '主键',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '3',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '类型',
                'after' => 'id',
            ])
            ->addColumn('code', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '编码',
                'after' => 'type',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'code',
            ])
            ->create();
        $this->table('kg_article', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
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
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '摘要',
                'after' => 'cover',
            ])
            ->addColumn('tags', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标签',
                'after' => 'summary',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'tags',
            ])
            ->addColumn('score', 'float', [
                'null' => false,
                'default' => '0.00',
                'comment' => '综合得分',
                'after' => 'content',
            ])
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '分类编号',
                'after' => 'score',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'category_id',
            ])
            ->addColumn('source_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '来源类型',
                'after' => 'owner_id',
            ])
            ->addColumn('source_url', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '来源网址',
                'after' => 'source_type',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'source_url',
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
            ->addColumn('private', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '私有标识',
                'after' => 'client_ip',
            ])
            ->addColumn('featured', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '推荐标识',
                'after' => 'private',
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
            ->addColumn('closed', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '关闭标识',
                'after' => 'deleted',
            ])
            ->addColumn('word_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文字数',
                'after' => 'closed',
            ])
            ->addColumn('view_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '浏览数',
                'after' => 'word_count',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数',
                'after' => 'view_count',
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
            ->addColumn('favorite_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '收藏数',
                'after' => 'report_count',
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
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_article_favorite', [
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
            ->addColumn('article_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文章编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'article_id',
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
            ->addIndex(['article_id'], [
                'name' => 'article_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_article_like', [
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
            ->addColumn('article_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文章编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'article_id',
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
            ->addIndex(['article_id', 'user_id'], [
                'name' => 'article_user',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_article_tag', [
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
            ->addColumn('article_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文章编号',
                'after' => 'id',
            ])
            ->addColumn('tag_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'article_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'tag_id',
            ])
            ->addIndex(['article_id'], [
                'name' => 'article_id',
                'unique' => false,
            ])
            ->addIndex(['tag_id'], [
                'name' => 'tag_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_audit', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名称',
                'after' => 'user_id',
            ])
            ->addColumn('user_ip', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户IP',
                'after' => 'user_name',
            ])
            ->addColumn('req_route', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求路由',
                'after' => 'user_ip',
            ])
            ->addColumn('req_path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '请求路径',
                'after' => 'req_route',
            ])
            ->addColumn('req_data', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
        $this->table('kg_category', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'type',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路径',
                'after' => 'name',
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
        $this->table('kg_chapter', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'course_id',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'title',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'summary',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
            ->addColumn('resource_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '资料数',
                'after' => 'deleted',
            ])
            ->addColumn('lesson_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课时数',
                'after' => 'resource_count',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学员数',
                'after' => 'lesson_count',
            ])
            ->addColumn('consult_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '咨询数',
                'after' => 'user_count',
            ])
            ->addColumn('comment_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论数',
                'after' => 'consult_count',
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
        $this->table('kg_chapter_like', [
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
            ->addIndex(['chapter_id', 'user_id'], [
                'name' => 'chapter_user',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_chapter_live', [
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
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'chapter_id',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addColumn('user_limit', 'integer', [
                'null' => false,
                'default' => '100',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户限额',
                'after' => 'end_time',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '2',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态标识',
                'after' => 'user_limit',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
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
        $this->table('kg_chapter_offline', [
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
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'chapter_id',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'end_time',
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
        $this->table('kg_chapter_read', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'chapter_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'content',
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
        $this->table('kg_chapter_user', [
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
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'consumed',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['chapter_id', 'user_id'], [
                'name' => 'chapter_user',
                'unique' => false,
            ])
            ->addIndex(['course_id', 'user_id'], [
                'name' => 'course_user',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_chapter_vod', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '文件编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('file_transcode', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '文件属性',
                'after' => 'file_id',
            ])
            ->addColumn('file_remote', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '远程资源',
                'after' => 'file_transcode',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'file_remote',
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
            ->addIndex(['file_id'], [
                'name' => 'file_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_comment', [
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
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'id',
            ])
            ->addColumn('parent_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '父级编号',
                'after' => 'content',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'parent_id',
            ])
            ->addColumn('to_user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '回复用户',
                'after' => 'owner_id',
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '条目编号',
                'after' => 'to_user_id',
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
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'client_ip',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('reply_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '回复数',
                'after' => 'deleted',
            ])
            ->addColumn('like_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '点赞数',
                'after' => 'reply_count',
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
            ->addIndex(['item_id', 'item_type'], [
                'name' => 'item',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['parent_id'], [
                'name' => 'parent_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_comment_like', [
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
            ->addColumn('comment_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评论编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'comment_id',
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
            ->addIndex(['comment_id', 'user_id'], [
                'name' => 'comment_user',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_connect', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('union_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'union_id',
                'after' => 'user_id',
            ])
            ->addColumn('open_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放ID',
                'after' => 'union_id',
            ])
            ->addColumn('open_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放名称',
                'after' => 'open_id',
            ])
            ->addColumn('open_avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 150,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放头像',
                'after' => 'open_name',
            ])
            ->addColumn('provider', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '提供方',
                'after' => 'open_avatar',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'provider',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['open_id', 'provider'], [
                'name' => 'open_provider',
                'unique' => false,
            ])
            ->addIndex(['union_id', 'provider'], [
                'name' => 'union_provider',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_consult', [
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
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('replier_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '回复者编号',
                'after' => 'owner_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'replier_id',
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
            ->addColumn('question', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '问题',
                'after' => 'client_ip',
            ])
            ->addColumn('answer', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '答案',
                'after' => 'question',
            ])
            ->addColumn('rating', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '评分',
                'after' => 'answer',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'rating',
            ])
            ->addColumn('private', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '私密标识',
                'after' => 'priority',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'private',
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
            ->addColumn('reply_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '回复时间',
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
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
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
        $this->table('kg_consult_like', [
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
            ->addColumn('consult_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '咨询编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'consult_id',
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
            ->addIndex(['consult_id', 'user_id'], [
                'name' => 'consult_user',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_course', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
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
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'summary',
            ])
            ->addColumn('details', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
            ->addColumn('origin_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '原始价格',
                'after' => 'teacher_id',
            ])
            ->addColumn('market_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '市场价格',
                'after' => 'origin_price',
            ])
            ->addColumn('vip_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
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
                'default' => '0.0000',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '扩展属性',
                'after' => 'level',
            ])
            ->addColumn('featured', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
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
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '学员数',
                'after' => 'resource_count',
            ])
            ->addColumn('lesson_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课时数',
                'after' => 'user_count',
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
            ->create();
        $this->table('kg_course_category', [
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
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '分类编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'category_id',
            ])
            ->addIndex(['category_id'], [
                'name' => 'category_id',
                'unique' => false,
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_course_favorite', [
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
        $this->table('kg_course_package', [
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
            ->addColumn('package_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '套餐编号',
                'after' => 'course_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'package_id',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['package_id'], [
                'name' => 'package_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_course_rating', [
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
        $this->table('kg_course_related', [
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
        $this->table('kg_course_topic', [
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
            ->addColumn('topic_id', 'integer', [
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
                'after' => 'topic_id',
            ])
            ->addIndex(['course_id'], [
                'name' => 'course_id',
                'unique' => false,
            ])
            ->addIndex(['topic_id'], [
                'name' => 'topic_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_course_user', [
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
            ->addColumn('role_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '角色类型',
                'after' => 'plan_id',
            ])
            ->addColumn('source_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '来源类型',
                'after' => 'role_type',
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
            ->addColumn('expiry_time', 'integer', [
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
            ->addIndex(['course_id', 'user_id'], [
                'name' => 'course_user',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_danmu', [
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
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '时间轴',
                'after' => 'owner_id',
            ])
            ->addColumn('text', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'time',
            ])
            ->addColumn('color', 'string', [
                'null' => false,
                'default' => 'white',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '颜色',
                'after' => 'text',
            ])
            ->addColumn('size', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '字号',
                'after' => 'color',
            ])
            ->addColumn('position', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '位置',
                'after' => 'size',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'position',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'cover',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addColumn('fuck', 'float', [
                'null' => false,
                'default' => '0.00',
                'after' => 'update_time',
            ])
            ->addIndex(['chapter_id'], [
                'name' => 'chapter_id',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_distribution', [
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
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '商品编号',
                'after' => 'id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '商品类型',
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '商品信息',
                'after' => 'item_type',
            ])
            ->addColumn('com_rate', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '佣金比例',
                'after' => 'item_info',
            ])
            ->addColumn('com_amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '佣金数额',
                'after' => 'com_rate',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'com_amount',
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
        $this->table('kg_flash_sale', [
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
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '商品编号',
                'after' => 'id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '商品类型',
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '商品信息',
                'after' => 'item_type',
            ])
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'item_info',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addColumn('schedules', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '抢购场次',
                'after' => 'end_time',
            ])
            ->addColumn('price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '抢购价格',
                'after' => 'schedules',
            ])
            ->addColumn('stock', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '抢购库存',
                'after' => 'price',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'stock',
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
            ->addIndex(['end_time'], [
                'name' => 'end_time',
                'unique' => false,
            ])
            ->addIndex(['start_time'], [
                'name' => 'start_time',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_help', [
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
            ->addColumn('category_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '分类编号',
                'after' => 'id',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'category_id',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'title',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '10',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'content',
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
        $this->table('kg_im_friend_group', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'user_id',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'name',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'priority',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '成员数',
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
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => true,
            ])
            ->create();
        $this->table('kg_im_friend_user', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('friend_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '目标编号',
                'after' => 'user_id',
            ])
            ->addColumn('group_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '分组编号',
                'after' => 'friend_id',
            ])
            ->addColumn('msg_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '消息数',
                'after' => 'group_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'msg_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['user_id', 'friend_id'], [
                'name' => 'user_friend',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_im_group', [
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
                'comment' => '群主编号',
                'after' => 'id',
            ])
            ->addColumn('course_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程编号',
                'after' => 'owner_id',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '类型',
                'after' => 'course_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'type',
            ])
            ->addColumn('avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '头像',
                'after' => 'name',
            ])
            ->addColumn('about', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'avatar',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'about',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('user_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '成员数',
                'after' => 'deleted',
            ])
            ->addColumn('msg_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '消息数',
                'after' => 'user_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'msg_count',
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
        $this->table('kg_im_group_user', [
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
            ->addColumn('group_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '群组编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'group_id',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'priority',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['group_id'], [
                'name' => 'group_id',
                'unique' => false,
            ])
            ->addIndex(['group_id', 'user_id'], [
                'name' => 'group_user',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_im_message', [
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
            ->addColumn('chat_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '对话编号',
                'after' => 'id',
            ])
            ->addColumn('sender_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发送方',
                'after' => 'chat_id',
            ])
            ->addColumn('receiver_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '接收方',
                'after' => 'sender_id',
            ])
            ->addColumn('receiver_type', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '接收方类型',
                'after' => 'receiver_id',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 3000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'receiver_type',
            ])
            ->addColumn('viewed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '已读标识',
                'after' => 'content',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'viewed',
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
            ->addIndex(['chat_id'], [
                'name' => 'chat_id',
                'unique' => false,
            ])
            ->addIndex(['receiver_id', 'receiver_type'], [
                'name' => 'receiver',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_im_notice', [
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
            ->addColumn('sender_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发送方',
                'after' => 'id',
            ])
            ->addColumn('receiver_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '接收方',
                'after' => 'sender_id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '条目类型',
                'after' => 'receiver_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '条目内容',
                'after' => 'item_type',
            ])
            ->addColumn('viewed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '已读标识',
                'after' => 'item_info',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'viewed',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['receiver_id'], [
                'name' => 'receiver_id',
                'unique' => false,
            ])
            ->addIndex(['sender_id'], [
                'name' => 'sender_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_im_user', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'id',
            ])
            ->addColumn('avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '头像',
                'after' => 'name',
            ])
            ->addColumn('sign', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '签名',
                'after' => 'avatar',
            ])
            ->addColumn('skin', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '皮肤',
                'after' => 'sign',
            ])
            ->addColumn('status', 'string', [
                'null' => false,
                'default' => 'hide',
                'limit' => 15,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '在线状态',
                'after' => 'skin',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'status',
            ])
            ->addColumn('friend_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '好友数',
                'after' => 'deleted',
            ])
            ->addColumn('group_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '群组数',
                'after' => 'friend_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'group_count',
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
        $this->table('kg_learning', [
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
            ->addColumn('request_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
            ->addIndex(['chapter_id', 'user_id'], [
                'name' => 'chapter_user',
                'unique' => false,
            ])
            ->addIndex(['request_id'], [
                'name' => 'request_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_nav', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'level',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路径',
                'after' => 'name',
            ])
            ->addColumn('target', 'string', [
                'null' => false,
                'default' => '_blank',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '打开方式',
                'after' => 'path',
            ])
            ->addColumn('url', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
        $this->table('kg_notification', [
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
            ->addColumn('sender_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发送方编号',
                'after' => 'id',
            ])
            ->addColumn('receiver_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '接收方编号',
                'after' => 'sender_id',
            ])
            ->addColumn('event_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '事件编号',
                'after' => 'receiver_id',
            ])
            ->addColumn('event_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '事件类型',
                'after' => 'event_id',
            ])
            ->addColumn('event_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '事件内容',
                'after' => 'event_type',
            ])
            ->addColumn('viewed', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '已读标识',
                'after' => 'event_info',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'viewed',
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
            ->addIndex(['receiver_id'], [
                'name' => 'receiver_id',
                'unique' => false,
            ])
            ->addIndex(['sender_id'], [
                'name' => 'sender_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_online', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
            ->addIndex(['active_time'], [
                'name' => 'active_time',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_order', [
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
            ->addColumn('sn', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '订单编号',
                'after' => 'id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '订单标题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '订单金额',
                'after' => 'subject',
            ])
            ->addColumn('owner_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'amount',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
            ->addIndex(['create_time'], [
                'name' => 'create_time',
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
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_order_status', [
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
        $this->table('kg_package', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('market_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '市场价格',
                'after' => 'summary',
            ])
            ->addColumn('vip_price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '会员价格',
                'after' => 'market_price',
            ])
            ->addColumn('course_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程数量',
                'after' => 'vip_price',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'course_count',
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
        $this->table('kg_page', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('content', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'title',
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
        $this->table('kg_point_gift', [
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
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'id',
            ])
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'name',
            ])
            ->addColumn('details', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '详情',
                'after' => 'cover',
            ])
            ->addColumn('attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '扩展属性',
                'after' => 'details',
            ])
            ->addColumn('type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '类型',
                'after' => 'attrs',
            ])
            ->addColumn('stock', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '库存',
                'after' => 'type',
            ])
            ->addColumn('point', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '所需积分',
                'after' => 'stock',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'point',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('redeem_limit', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '兑换限额',
                'after' => 'deleted',
            ])
            ->addColumn('redeem_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '兑换数量',
                'after' => 'redeem_limit',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'redeem_count',
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
        $this->table('kg_point_history', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('user_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名称',
                'after' => 'user_id',
            ])
            ->addColumn('event_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '事件编号',
                'after' => 'user_name',
            ])
            ->addColumn('event_type', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '事件类型',
                'after' => 'event_id',
            ])
            ->addColumn('event_info', 'string', [
                'null' => false,
                'default' => '0',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '事件内容',
                'after' => 'event_type',
            ])
            ->addColumn('event_point', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '事件积分',
                'after' => 'event_info',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'event_point',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['event_id', 'event_type'], [
                'name' => 'event',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_point_redeem', [
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
                'identity' => 'enable',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '用户名称',
                'after' => 'user_id',
            ])
            ->addColumn('gift_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '礼品编号',
                'after' => 'user_name',
            ])
            ->addColumn('gift_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '礼品名称',
                'after' => 'gift_id',
            ])
            ->addColumn('gift_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '礼品类型',
                'after' => 'gift_name',
            ])
            ->addColumn('gift_point', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '礼品积分',
                'after' => 'gift_type',
            ])
            ->addColumn('contact_name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '联系人',
                'after' => 'gift_point',
            ])
            ->addColumn('contact_phone', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '联系电话',
                'after' => 'contact_name',
            ])
            ->addColumn('contact_address', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '联系地址',
                'after' => 'contact_phone',
            ])
            ->addColumn('remark', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '备注',
                'after' => 'contact_address',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态标识',
                'after' => 'remark',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'status',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['gift_id'], [
                'name' => 'gift_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
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
                'comment' => '最后回答编号',
                'after' => 'last_replier_id',
            ])
            ->addColumn('accept_answer_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '采纳回答编号',
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
                'after' => 'last_reply_time',
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
            ->addIndex(['last_reply_time'], [
                'name' => 'last_reply_time',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->create();
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
            ->addIndex(['question_id'], [
                'name' => 'question_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
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
            ->addIndex(['question_id'], [
                'name' => 'question_id',
                'unique' => false,
            ])
            ->addIndex(['tag_id'], [
                'name' => 'tag_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_refund', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '退款序号',
                'after' => 'trade_id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '退款主题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '退款金额',
                'after' => 'subject',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态类型',
                'after' => 'amount',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '申请备注',
                'after' => 'deleted',
            ])
            ->addColumn('review_note', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '审核备注',
                'after' => 'apply_note',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'review_note',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->addIndex(['trade_id'], [
                'name' => 'trade_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_refund_status', [
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
            ->addIndex(['item_id', 'item_type'], [
                'name' => 'item',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_resource', [
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
            ->addColumn('chapter_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '章节编号',
                'after' => 'course_id',
            ])
            ->addColumn('upload_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '上传编号',
                'after' => 'chapter_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'upload_id',
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
        $this->table('kg_review', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '终端IP',
                'after' => 'client_type',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'client_ip',
            ])
            ->addColumn('reply', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
        $this->table('kg_review_like', [
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
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'user_id',
            ])
            ->addIndex(['review_id', 'user_id'], [
                'name' => 'review_user',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_reward', [
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
                'limit' => MysqlAdapter::INT_SMALL,
                'comment' => '主键编号',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '价格',
                'after' => 'title',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'price',
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
        $this->table('kg_role', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'type',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'name',
            ])
            ->addColumn('routes', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
        $this->table('kg_setting', [
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
            ->addColumn('section', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置组',
                'after' => 'id',
            ])
            ->addColumn('item_key', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置项',
                'after' => 'section',
            ])
            ->addColumn('item_value', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '配置值',
                'after' => 'item_key',
            ])
            ->addIndex(['section', 'item_key'], [
                'name' => 'section_key',
                'unique' => true,
            ])
            ->create();
        $this->table('kg_slide', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
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
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'cover',
            ])
            ->addColumn('content', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '内容',
                'after' => 'summary',
            ])
            ->addColumn('platform', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '平台类型',
                'after' => 'content',
            ])
            ->addColumn('target', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '目标类型',
                'after' => 'platform',
            ])
            ->addColumn('target_attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '目标属性',
                'after' => 'target',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '10',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'target_attrs',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布状态',
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
        $this->table('kg_tag', [
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
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'id',
            ])
            ->addColumn('alias', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '别名',
                'after' => 'name',
            ])
            ->addColumn('icon', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '图标',
                'after' => 'alias',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'icon',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '1',
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
            ->addColumn('follow_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '关注数',
                'after' => 'deleted',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'follow_count',
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
        $this->table('kg_tag_follow', [
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
            ->addColumn('tag_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'id',
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '标签编号',
                'after' => 'tag_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'user_id',
            ])
            ->addIndex(['tag_id'], [
                'name' => 'tag_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_task', [
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
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '条目编号',
                'after' => 'id',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '条目内容',
                'after' => 'item_type',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '状态',
                'after' => 'item_info',
            ])
            ->addColumn('priority', 'integer', [
                'null' => false,
                'default' => '30',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '优先级',
                'after' => 'status',
            ])
            ->addColumn('try_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '重试数',
                'after' => 'priority',
            ])
            ->addColumn('max_try_count', 'integer', [
                'null' => false,
                'default' => '3',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '最大重试数',
                'after' => 'try_count',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'max_try_count',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['create_time'], [
                'name' => 'create_time',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_topic', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
            ])
            ->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'title',
            ])
            ->addColumn('summary', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'keywords',
            ])
            ->addColumn('course_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程数量',
                'after' => 'summary',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'course_count',
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
        $this->table('kg_trade', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '交易序号',
                'after' => 'order_id',
            ])
            ->addColumn('subject', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '交易主题',
                'after' => 'sn',
            ])
            ->addColumn('amount', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '平台序号',
                'after' => 'channel',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '状态类型',
                'after' => 'channel_sn',
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
            ->addIndex(['order_id'], [
                'name' => 'order_id',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'owner_id',
                'unique' => false,
            ])
            ->addIndex(['sn'], [
                'name' => 'sn',
                'unique' => false,
            ])
            ->create();
        $this->table('kg_trade_status', [
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
        $this->table('kg_upload', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '文件名',
                'after' => 'type',
            ])
            ->addColumn('path', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '路径',
                'after' => 'name',
            ])
            ->addColumn('mime', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => 'mime',
                'after' => 'path',
            ])
            ->addColumn('md5', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 32,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
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
                'unique' => true,
            ])
            ->create();
        $this->table('kg_user', [
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
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '名称',
                'after' => 'id',
            ])
            ->addColumn('avatar', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '头像',
                'after' => 'name',
            ])
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '头衔',
                'after' => 'avatar',
            ])
            ->addColumn('about', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '简介',
                'after' => 'title',
            ])
            ->addColumn('area', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '地区',
                'after' => 'about',
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
            ->addColumn('course_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '课程数',
                'after' => 'admin_role',
            ])
            ->addColumn('article_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '文章数',
                'after' => 'course_count',
            ])
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
            ->addColumn('report_count', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '举报数',
                'after' => 'favorite_count',
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
        $this->table('kg_user_balance', [
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
                'precision' => '10',
                'scale' => '2',
                'comment' => '可用现金',
                'after' => 'user_id',
            ])
            ->addColumn('point', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'comment' => '可用积分',
                'after' => 'cash',
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
        $this->table('kg_user_contact', [
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
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '姓名',
                'after' => 'user_id',
            ])
            ->addColumn('phone', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '电话',
                'after' => 'name',
            ])
            ->addColumn('add_province', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '地址(省)',
                'after' => 'phone',
            ])
            ->addColumn('add_city', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '地址(市)',
                'after' => 'add_province',
            ])
            ->addColumn('add_county', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '地址(区)',
                'after' => 'add_city',
            ])
            ->addColumn('add_other', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '地址(详)',
                'after' => 'add_county',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'add_other',
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
        $this->table('kg_user_session', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('session_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '会话编号',
                'after' => 'user_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'session_id',
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
            ->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'client_ip',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'expire_time',
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
            ->create();
        $this->table('kg_user_token', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('token', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '身份令牌',
                'after' => 'user_id',
            ])
            ->addColumn('client_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '终端类型',
                'after' => 'token',
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
            ->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'client_ip',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'expire_time',
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
            ->create();
        $this->table('kg_vip', [
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
            ->addColumn('title', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 30,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '标题',
                'after' => 'id',
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
                'precision' => '10',
                'scale' => '2',
                'comment' => '价格',
                'after' => 'expiry',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'price',
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
        $this->table('kg_wechat_subscribe', [
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
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '用户编号',
                'after' => 'id',
            ])
            ->addColumn('open_id', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '开放ID',
                'after' => 'user_id',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'open_id',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['open_id'], [
                'name' => 'open_id',
                'unique' => false,
            ])
            ->addIndex(['user_id'], [
                'name' => 'user_id',
                'unique' => false,
            ])
            ->create();
    }

}
