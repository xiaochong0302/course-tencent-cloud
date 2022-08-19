<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'SettingTrait.php';

use Phinx\Migration\AbstractMigration;

final class V20210820064755 extends AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->handleContactSettings();
    }

    protected function handleContactSettings()
    {
        $rows = [
            [
                'section' => 'contact',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
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

        $this->insertSettings($rows);
    }

}
