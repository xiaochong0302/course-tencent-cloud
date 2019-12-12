<?php

namespace App\Caches;

use App\Repos\Config as ConfigRepo;

class Config extends Cache
{

    protected $lifetime = 365 * 86400;

    /**
     * 获取某组配置项
     *
     * @param string $section
     * @return \stdClass|null
     */
    public function getSectionConfig($section)
    {
        $items = $this->get();

        if (!$items) return;

        $result = new \stdClass();

        foreach ($items as $item) {
            if ($item->section == $section) {
                $result->{$item->item_key} = $item->item_value;
            }
        }

        return $result;
    }

    /**
     * 获取某个配置项的值
     *
     * @param string $section
     * @param string $key
     * @return string|null
     */
    public function getItemValue($section, $key)
    {
        $config = $this->getSectionConfig($section);

        $result = $config->{$key} ?? null;

        return $result;
    }

    protected function getLifetime()
    {
        return $this->lifetime;
    }

    protected function getKey($params = null)
    {
        return 'site_config';
    }

    protected function getContent($params = null)
    {
        $configRepo = new ConfigRepo();

        $items = $configRepo->findAll();

        return $items;
    }

}
