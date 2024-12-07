<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

class V20241110191316 extends Migration
{

    public function run()
    {
        $this->handleContactSettings();
    }

    protected function handleContactSettings()
    {
        $setting = [
            'section' => 'contact',
            'item_key' => 'douyin',
            'item_value' => '',
        ];

        $this->saveSetting($setting);
    }

}