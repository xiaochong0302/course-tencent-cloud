<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Setting as SettingModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Setting extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|SettingModel[]
     */
    public function findAll($where = [])
    {
        $query = SettingModel::query();

        $query->where('1 = 1');

        if (!empty($where['section'])) {
            $query->andWhere('section = :section:', ['section' => $where['section']]);
        }

        return $query->execute();
    }

    /**
     * @param string $section
     * @param string $itemKey
     * @return SettingModel|Model|bool
     */
    public function findItem($section, $itemKey)
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
    public function findBySection($section)
    {
        $query = SettingModel::query();

        $query->where('section = :section:', ['section' => $section]);

        return $query->execute();
    }

}
