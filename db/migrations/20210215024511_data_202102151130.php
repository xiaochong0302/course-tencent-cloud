<?php

class Data202102151130 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'app_secret',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'app_token',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'ts_mobiles',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'cs_mobiles',
                'item_value' => '',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();

        $this->updateImGroupRouter();
    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'dingtalk.robot'])
            ->execute();
    }

    protected function updateImGroupRouter()
    {
        $roles = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_role')
            ->execute();

        if ($roles->count() == 0) return;

        foreach ($roles as $role) {
            if (strpos($role['routes'], 'admin.group') !== false) {
                $routes = str_replace('admin.group', 'admin.im_group', $role['routes']);
                $this->getQueryBuilder()
                    ->update('kg_role')
                    ->set(['routes' => $routes])
                    ->where(['id' => $role['id']])
                    ->execute();
            }
        }
    }

}
