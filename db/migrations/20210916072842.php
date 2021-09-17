<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20210916072842 extends AbstractMigration
{

    public function up()
    {
        $this->handleLocalAuthSetting();
    }

    protected function handleLocalAuthSetting()
    {
        $rows = [
            [
                'section' => 'oauth.local',
                'item_key' => 'login_with_phone',
                'item_value' => '1',
            ],
            [
                'section' => 'oauth.local',
                'item_key' => 'login_with_email',
                'item_value' => '1',
            ]
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

}
