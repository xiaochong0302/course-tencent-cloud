<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertUserData extends AbstractMigration
{

    public function up()
    {
        $now = time();

        $account = [
            'id' => 10000,
            'email' => '10000@163.com',
            'password' => '772b6a120699280eb2cce32ec706656a',
            'salt' => 'MbZWxN3L',
            'create_time' => $now,
        ];

        $this->table('kg_account')->insert($account)->saveData();

        $user = [
            'id' => $account['id'],
            'name' => '酷瓜云网课',
            'avatar' => '/img/avatar/default.png',
            'title' => '官方人员',
            'about' => '酷瓜云课堂，依托腾讯云基础服务，使用C扩展框架PHALCON开发',
            'admin_role' => 1,
            'edu_role' => 2,
            'create_time' => $now,
        ];

        $this->table('kg_user')->insert($user)->saveData();

        $imUser = [
            'id' => $user['id'],
            'name' => $user['name'],
            'avatar' => $user['avatar'],
            'create_time' => $now,
        ];

        $this->table('kg_im_user')->insert($imUser)->saveData();
    }

    public function down()
    {
        $id = 10000;

        $sql = sprintf('DELETE FROM kg_account WHERE id = %d', $id);

        $this->execute($sql);

        $sql = sprintf('DELETE FROM kg_user WHERE id = %d', $id);

        $this->execute($sql);

        $sql = sprintf('DELETE FROM kg_im_user WHERE id = %d', $id);

        $this->execute($sql);
    }

}
