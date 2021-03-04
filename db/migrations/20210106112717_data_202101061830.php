<?php

use Phinx\Migration\AbstractMigration;

final class Data202101061830 extends AbstractMigration
{

    public function up()
    {
        $menu = [
            [
                'name' => '菜单1',
                'url' => '',
                'children' => [
                    [
                        'type' => 'view',
                        'name' => '菜单1-1',
                        'url' => 'https://gitee.com/koogua'
                    ],
                ],
            ],
            [
                'name' => '菜单2',
                'url' => '',
                'children' => [
                    [
                        'type' => 'view',
                        'name' => '菜单2-1',
                        'url' => 'https://gitee.com/koogua'
                    ],
                ],
            ],
            [
                'name' => '菜单3',
                'url' => '',
                'children' => [
                    [
                        'type' => 'view',
                        'name' => '菜单3-1',
                        'url' => 'https://gitee.com/koogua'
                    ],
                ],
            ],
        ];

        $rows = [
            [
                'section' => 'wechat.oa',
                'item_key' => 'menu',
                'item_value' => json_encode($menu),
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();

    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'wechat.oa', 'item_key' => 'menu'])
            ->execute();
    }

}