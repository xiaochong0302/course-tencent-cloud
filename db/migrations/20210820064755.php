<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20210820064755 extends AbstractMigration
{

    public function up()
    {
        $this->handleContactSetting();
    }

    protected function handleContactSetting()
    {
        $rows = [
            [
                'section' => 'contact',
                'item_key' => 'qq',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'wechat',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'toutiao',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'weibo',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'zhihu',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'email',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'phone',
                'item_value' => '',
            ],
            [
                'section' => 'contact',
                'item_key' => 'address',
                'item_value' => '',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

}
