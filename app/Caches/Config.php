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
     * @return array
     */
    public function getSectionConfig($section)
    {
        $items = $this->get();

        $result = [];

        if (!$items) {
            return $result;
        }

        foreach ($items as $item) {
            if ($item['section'] == $section) {
                $result[$item['item_key']] = $item['item_value'];
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

        $result = $config[$key] ?? null;

        return $result;
    }

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'config';
    }

    public function getContent($id = null)
    {
        $configRepo = new ConfigRepo();

        $items = $configRepo->findAll();

        return $items->toArray();
    }

}
