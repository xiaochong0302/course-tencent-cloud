<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

class V20261025191621 extends Migration
{

    public function run(): void
    {
        $this->handleBizSettings();
    }

    protected function handleBizSettings(): void
    {
        $this->saveSetting('biz.editor', 'save_remote_image', '0');
    }

}
