<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class V20210422023157 extends AbstractMigration
{

    public function up()
    {
        $this->createNotificationTable();
        $this->modifyArticleTable();
        $this->modifyConsultTable();
        $this->modifyReviewTable();
        $this->handleArticlePublishStatus();
        $this->handleCommentPublishStatus();
        $this->handlePointHistoryEventInfo();
        $this->handleImNoticeItemInfo();
        $this->handlePointEventRules();
        $this->handleChapterAttrs();
    }

    public function down()
    {
        $this->table('kg_notification')->drop()->save();

        $this->table('kg_article')
            ->removeColumn('client_type')
            ->removeColumn('client_ip')
            ->save();

        $this->table('kg_consult')
            ->removeColumn('client_type')
            ->removeColumn('client_ip')
            ->save();

        $this->table('kg_review')
            ->removeColumn('client_type')
            ->removeColumn('client_ip')
            ->save();
    }

    protected function createNotificationTable()
    {
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
            ->addIndex(['sender_id'], [
                'name' => 'sender_id',
                'unique' => false,
            ])
            ->addIndex(['receiver_id'], [
                'name' => 'receiver_id',
                'unique' => false,
            ])
            ->create();
    }

    protected function modifyArticleTable()
    {
        $this->table('kg_article')
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
            ])->addColumn('private', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '私有标识',
                'after' => 'client_ip',
            ])->save();
    }

    protected function modifyConsultTable()
    {
        $this->table('kg_consult')
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
            ])->save();
    }

    protected function modifyReviewTable()
    {
        $this->table('kg_review')
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
            ])->save();
    }

    protected function handleArticlePublishStatus()
    {
        $this->getQueryBuilder()
            ->update('kg_article')
            ->set('published', 2)
            ->where(['published' => 1])
            ->execute();

        $this->getQueryBuilder()
            ->update('kg_article')
            ->set('published', 1)
            ->where(['published' => 0])
            ->execute();
    }

    protected function handleCommentPublishStatus()
    {
        $this->getQueryBuilder()
            ->update('kg_comment')
            ->set('published', 2)
            ->where(['published' => 1])
            ->execute();

        $this->getQueryBuilder()
            ->update('kg_comment')
            ->set('published', 1)
            ->where(['published' => 0])
            ->execute();
    }

    protected function handlePointEventRules()
    {
        $setting = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => 'point', 'item_key' => 'event_rule'])
            ->execute()->fetch('assoc');

        if (!$setting) return;

        $itemValue = json_decode($setting['item_value'], true);

        $itemValue['comment_post'] = ['point' => 2, 'enabled' => 1, 'limit' => 10];
        $itemValue['article_post'] = ['point' => 20, 'enabled' => 1, 'limit' => 50];
        $itemValue['question_post'] = ['point' => 5, 'enabled' => 1, 'limit' => 50];
        $itemValue['answer_post'] = ['point' => 5, 'enabled' => 1, 'limit' => 50];

        $itemValue = json_encode($itemValue);

        $this->getQueryBuilder()
            ->update('kg_setting')
            ->where(['id' => $setting['id']])
            ->set('item_value', $itemValue)
            ->execute();
    }

    protected function handleChapterAttrs()
    {
        $this->getQueryBuilder()
            ->update('kg_chapter')
            ->set('attrs', '{}')
            ->where(['parent_id' => 0, 'attrs' => ''])
            ->execute();
    }

    protected function handleImNoticeItemInfo()
    {
        $this->getQueryBuilder()
            ->update('kg_im_notice')
            ->set('item_info', '{}')
            ->where(['item_info' => ''])
            ->execute();
    }

    protected function handlePointHistoryEventInfo()
    {
        $this->getQueryBuilder()
            ->update('kg_point_history')
            ->set('event_info', '{}')
            ->whereInList('event_type', [4, 5, 8])
            ->execute();
    }

}
