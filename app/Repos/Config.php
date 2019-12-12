<?php

namespace App\Repos;

use App\Models\Config as ConfigModel;

class Config extends Repository
{

    public function findItem($section, $key)
    {
        $result = ConfigModel::query()
            ->where('section = :section:', ['section' => $section])
            ->andWhere('item_key = :key:', ['key' => $key])
            ->execute()->getFirst();

        return $result;
    }

    public function findBySection($section)
    {
        $query = ConfigModel::query();

        $query->where('section = :section:', ['section' => $section]);

        $result = $query->execute();

        return $result;
    }

    public function findAll($where = [])
    {
        $query = ConfigModel::query();

        $query->where('1 = 1');

        if (isset($where['section'])) {
            $query->andWhere('section = :section:', ['section' => $where['section']]);
        }

        $result = $query->execute();

        return $result;
    }

}
