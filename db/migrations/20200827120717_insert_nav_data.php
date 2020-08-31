<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertNavData extends AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'id' => 1,
                'parent_id' => 0,
                'level' => 1,
                'name' => '首页',
                'path' => ',1,',
                'target' => '_self',
                'url' => '/',
                'position' => 1,
                'priority' => 1,
                'published' => 1,
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'level' => 1,
                'name' => '录播',
                'path' => ',2,',
                'target' => '_self',
                'url' => '/course/list?model=1',
                'position' => 1,
                'priority' => 2,
                'published' => 1,
            ],
            [
                'id' => 3,
                'parent_id' => 0,
                'level' => 1,
                'name' => '直播',
                'path' => ',3,',
                'target' => '_self',
                'url' => '/course/list?model=2',
                'position' => 1,
                'priority' => 3,
                'published' => 1,
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'level' => 1,
                'name' => '专栏',
                'path' => ',4,',
                'target' => '_self',
                'url' => '/course/list?model=3',
                'position' => 1,
                'priority' => 4,
                'published' => 1,
            ],
            [
                'id' => 5,
                'parent_id' => 0,
                'level' => 1,
                'name' => '名师',
                'path' => ',5,',
                'target' => '_self',
                'url' => '/teacher/list',
                'position' => 1,
                'priority' => 5,
                'published' => 1,
            ],
            [
                'id' => 6,
                'parent_id' => 0,
                'level' => 1,
                'name' => '群组',
                'path' => ',6,',
                'target' => '_self',
                'url' => '/im/group/list',
                'position' => 1,
                'priority' => 6,
                'published' => 1,
            ],
            [
                'id' => 7,
                'parent_id' => 0,
                'level' => 1,
                'name' => '关于我们',
                'path' => ',7,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 1,
                'published' => 1,
            ],
            [
                'id' => 8,
                'parent_id' => 0,
                'level' => 1,
                'name' => '联系我们',
                'path' => ',8,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 2,
                'published' => 1,
            ],
            [
                'id' => 9,
                'parent_id' => 0,
                'level' => 1,
                'name' => '人才招聘',
                'path' => ',9,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 3,
                'published' => 1,
            ],
            [
                'id' => 10,
                'parent_id' => 0,
                'level' => 1,
                'name' => '帮助中心',
                'path' => ',10,',
                'target' => '_blank',
                'url' => '/help',
                'position' => 2,
                'priority' => 4,
                'published' => 1,
            ],
            [
                'id' => 11,
                'parent_id' => 0,
                'level' => 1,
                'name' => '友情链接',
                'path' => ',11,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 5,
                'published' => 1,
            ],
        ];

        $this->table('kg_nav')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_nav');
    }

}
