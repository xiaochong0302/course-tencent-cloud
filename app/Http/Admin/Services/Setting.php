<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\Setting as SettingCache;
use App\Repos\Setting as SettingRepo;

class Setting extends Service
{

    public function getSiteSettings(): array
    {
        $site = $this->getMySettings('site');

        $site['url'] = $site['url'] ?: kg_site_url();

        return $site;
    }

    public function getAlipaySettings(): array
    {
        $alipay = $this->getMySettings('pay.alipay');

        $alipay['return_url'] = $alipay['return_url'] ?: kg_full_url(['for' => 'home.alipay.callback']);
        $alipay['notify_url'] = $alipay['notify_url'] ?: kg_full_url(['for' => 'home.alipay.notify']);

        return $alipay;
    }

    public function getWxpaySettings(): array
    {
        $wxpay = $this->getMySettings('pay.wxpay');

        $wxpay['return_url'] = $wxpay['return_url'] ?: kg_full_url(['for' => 'home.wxpay.callback']);
        $wxpay['notify_url'] = $wxpay['notify_url'] ?: kg_full_url(['for' => 'home.wxpay.notify']);

        return $wxpay;
    }

    public function getMySettings(string $section): array
    {
        $settingsRepo = new SettingRepo();

        $items = $settingsRepo->findBySection($section);

        $result = [];

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $result[$item->item_key] = $item->item_value;
            }
        }

        return $result;
    }

    public function updateMySettings(string $section, array $settings): void
    {
        $settingsRepo = new SettingRepo();

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                array_walk_recursive($value, function (&$item) {
                    $item = trim($item);
                });
                $itemValue = kg_json_encode($value);
            } else {
                $itemValue = trim($value);
            }
            $item = $settingsRepo->findItem($section, $key);
            if ($item) {
                $item->item_value = $itemValue;
                $item->update();
            }
        }

        $cache = new SettingCache();

        $cache->rebuild($section);
    }

    public function updateStorageSettings(string $section, array $settings): void
    {
        $protocol = ['http://', 'https://'];

        if (isset($settings['domain'])) {
            $settings['domain'] = str_replace($protocol, '', $settings['domain']);
        }

        $this->updateMySettings($section, $settings);
    }

}
