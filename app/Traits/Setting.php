<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Traits;

use App\Models\Setting as SettingModel;
use App\Repos\Setting as SettingRepo;

trait Setting
{

    protected function saveSettings(string $section, array $settings, bool $overwrite = false): void
    {
        foreach ($settings as $key => $value) {
            $this->saveSetting($section, $key, $value, $overwrite);
        }
    }

    protected function deleteSettings(string $section, array $keys): void
    {
        foreach ($keys as $key) {
            $this->deleteSetting($section, $key);
        }
    }

    protected function findSetting(string $section, string $itemKey)
    {
        $settingRepo = new SettingRepo();

        return $settingRepo->findItem($section, $itemKey);
    }

    protected function deleteSetting(string $section, string $itemKey): void
    {
        $setting = $this->findSetting($section, $itemKey);

        if (!$setting) return;

        $setting->delete();
    }

    protected function saveSetting(string $section, string $itemKey, array|string $itemValue, bool $overwrite = false): void
    {
        if (is_array($itemValue)) {
            $itemValue = kg_json_encode($itemValue);
        }

        $item = $this->findSetting($section, $itemKey);

        if (!$item) {
            $newItem = new SettingModel();
            $newItem->section = $section;
            $newItem->item_key = $itemKey;
            $newItem->item_value = $itemValue;
            $newItem->create();
        } else {
            if ($overwrite) {
                $item->item_value = $itemValue;
                $item->update();
            }
        }
    }

}
