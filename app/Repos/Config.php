<?php

namespace App\Repos;

use App\Models\Config as ConfigModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Config extends Repository
{

    /**
     * @param array $where
     * @return Resultset|ResultsetInterface|ConfigModel[]
     */
    public function findAll($where = [])
    {
        $query = ConfigModel::query();

        $query->where('1 = 1');

        if (!empty($where['section'])) {
            $query->andWhere('section = :section:', ['section' => $where['section']]);
        }

        return $query->execute();
    }

    /**
     * @param string $section
     * @param string $itemKey
     * @return ConfigModel|Model|bool
     */
    public function findItem($section, $itemKey)
    {
        return ConfigModel::findFirst([
            'conditions' => 'section = :section: AND item_key = :item_key:',
            'bind' => ['section' => $section, 'item_key' => $itemKey],
        ]);
    }

    /**
     * @param string $section
     * @return Resultset|ResultsetInterface|ConfigModel[]
     */
    public function findBySection($section)
    {
        $query = ConfigModel::query();

        $query->where('section = :section:', ['section' => $section]);

        return $query->execute();
    }

}
