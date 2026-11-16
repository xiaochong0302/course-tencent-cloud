<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Setting as SettingRepo;

class Setting extends Cache
{

    /**
     * 进程内缓存（同一请求/任务内复用，避免重复读 Redis）
     *
     * @var array
     */
    protected static array $memo = [];

    /**
     * @var int
     */
    protected int $lifetime = 365 * 86400;

    public function get($id = null): mixed
    {
        $key = $this->getKey($id);

        if (!array_key_exists($key, self::$memo)) {
            self::$memo[$key] = parent::get($id) ?? [];
        }

        return self::$memo[$key];
    }

    public function delete($id = null): bool
    {
        unset(self::$memo[$this->getKey($id)]);

        return parent::delete($id);
    }

    public function getKey($id = null): string
    {
        return "setting-{$id}";
    }

    public function getContent($id = null): array
    {
        $settingRepo = new SettingRepo();

        $items = $settingRepo->findBySection($id);

        if ($items->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item->item_key] = $item->item_value;
        }

        return $result;
    }

}
