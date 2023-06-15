<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

trait SettingTrait
{

    protected function insertSettings(array $rows)
    {
        foreach ($rows as $key => $row) {
            $exists = $this->settingExists($row['section'], $row['item_key']);
            if ($exists) unset($rows[$key]);
        }

        if (count($rows) == 0) return;

        $this->table('kg_setting')->insert($rows)->save();
    }

    protected function settingExists($section, $itemKey)
    {
        $row = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => $section, 'item_key' => $itemKey])
            ->execute()->fetch();

        return (bool)$row;
    }

}