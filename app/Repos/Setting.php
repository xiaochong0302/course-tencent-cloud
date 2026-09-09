<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Setting as SettingModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Setting extends Repository
{

    /**
     * @param string $section
     * @param string $itemKey
     * @return SettingModel|Row|null
     */
    public function findItem(string $section, string $itemKey)
    {
        return SettingModel::findFirst([
            'conditions' => 'section = :section: AND item_key = :item_key:',
            'bind' => ['section' => $section, 'item_key' => $itemKey],
        ]);
    }

    /**
     * @param string $section
     * @return ResultsetInterface|Resultset|SettingModel[]
     */
    public function findBySection(string $section)
    {
        return SettingModel::query()
            ->where('section = :section:', ['section' => $section])
            ->execute();
    }

}
