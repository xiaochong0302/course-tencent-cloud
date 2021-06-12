<?php

use Phinx\Migration\AbstractMigration;

final class V20210610034658 extends AbstractMigration
{

    public function up()
    {
        $this->modifyChapterVodTable();
        $this->handleVodFileRemote();
    }

    public function down()
    {
        $this->table('kg_chapter_vod')
            ->removeColumn('file_remote')
            ->save();
    }

    protected function modifyChapterVodTable()
    {
        $this->table('kg_chapter_vod')
            ->addColumn('file_remote', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1500,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '远程文件',
                'after' => 'file_transcode',
            ])->save();
    }

    protected function handleVodFileRemote()
    {
        $this->getQueryBuilder()
            ->update('kg_chapter_vod')
            ->set('file_remote', '[]')
            ->where(['file_remote' => ''])
            ->execute();
    }

}
