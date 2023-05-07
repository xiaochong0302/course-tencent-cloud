<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'PageTrait.php';

use Phinx\Migration\AbstractMigration;

final class V20220915084746 extends AbstractMigration
{

    use PageTrait;

    public function up()
    {
        $this->alterArticleTable();
        $this->alterQuestionTable();
        $this->alterTopicTable();
        $this->alterPageTable();
        $this->alterHelpTable();
        $this->handleTopics();
        $this->handleProtocolPages();
    }

    protected function alterArticleTable()
    {
        $table = $this->table('kg_article');

        if (!$table->hasColumn('keywords')) {
            $table->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'summary',
            ]);
        }

        $table->save();
    }

    protected function alterQuestionTable()
    {
        $table = $this->table('kg_question');

        if (!$table->hasColumn('keywords')) {
            $table->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'tags',
            ]);
        }

        $table->save();
    }

    protected function alterPageTable()
    {
        $table = $this->table('kg_page');

        if (!$table->hasColumn('keywords')) {
            $table->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'alias',
            ]);
        }

        $table->save();
    }

    protected function alterHelpTable()
    {
        $table = $this->table('kg_help');

        if (!$table->hasColumn('keywords')) {
            $table->addColumn('keywords', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '关键字',
                'after' => 'title',
            ]);
        }

        $table->save();
    }

    protected function alterTopicTable()
    {
        $table = $this->table('kg_topic');

        if (!$table->hasColumn('cover')) {
            $table->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'title',
            ]);
        }

        $table->save();
    }

    protected function handleTopics()
    {
        $this->getQueryBuilder()
            ->update('kg_topic')
            ->set('cover', '/img/default/topic_cover.png')
            ->where(['cover' => ''])
            ->execute();
    }

    protected function handleProtocolPages()
    {
        $rows = [
            [
                'title' => '用户协议',
                'alias' => 'terms',
                'content' => '',
                'published' => 1,
                'create_time' => time(),
            ],
            [
                'title' => '隐私政策',
                'alias' => 'privacy',
                'content' => '',
                'published' => 1,
                'create_time' => time(),
            ],
        ];

        $this->insertPages($rows);
    }

}
