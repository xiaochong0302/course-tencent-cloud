<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Models\Setting as SettingModel;
use App\Repos\Setting as SettingRepo;
use App\Traits\Service as ServiceTrait;

abstract class Migration
{

    use ServiceTrait;

    abstract public function run();

    protected function saveSettings(array $settings)
    {
        foreach ($settings as $setting) {
            $this->saveSetting($setting);
        }
    }

    protected function saveSetting(array $setting)
    {
        $settingRepo = new SettingRepo();

        $item = $settingRepo->findItem($setting['section'], $setting['item_key']);

        if (!$item) {
            $item = new SettingModel();
            $item->create($setting);
        } else {
            $item->update($setting);
        }
    }

}
