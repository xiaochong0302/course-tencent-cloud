<?php

namespace App\Http\Admin\Services;

use App\Caches\Setting as SettingCache;
use App\Repos\Setting as SettingRepo;
use App\Repos\Vip as VipRepo;

class Setting extends Service
{

    public function getAlipaySettings()
    {
        $alipay = $this->getSectionSettings('pay.alipay');

        $alipay['notify_url'] = $alipay['notify_url'] ?: kg_full_url(['for' => 'desktop.alipay_notify']);

        return $alipay;
    }

    public function getWxpaySettings()
    {
        $wxpay = $this->getSectionSettings('pay.wxpay');

        $wxpay['notify_url'] = $wxpay['notify_url'] ?: kg_full_url(['for' => 'desktop.wxpay_notify']);

        return $wxpay;
    }

    public function getVipSettings()
    {
        $vipRepo = new VipRepo();

        return $vipRepo->findAll(['deleted' => 0]);
    }

    public function getLiveSettings($section)
    {
        $result = $this->getSectionSettings($section);

        if ($section == 'live.notify') {
            $result['stream_begin_url'] = $result['stream_begin_url'] ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'streamBegin']);
            $result['stream_end_url'] = $result['stream_end_url'] ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'streamEnd']);
            $result['record_url'] = $result['record_url'] ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'record']);
            $result['snapshot_url'] = $result['snapshot_url'] ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'snapshot']);
            $result['porn_url'] = $result['porn_url'] ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'porn']);
        }

        return $result;
    }

    public function getSectionSettings($section)
    {
        $settingsRepo = new SettingRepo();

        $items = $settingsRepo->findBySection($section);

        $result = [];

        /**
         * demo分支过滤敏感数据
         */
        if ($items->count() > 0) {
            foreach ($items as $item) {
                $pattern = '/(auth|key|secret|pwd|password)/';
                if (preg_match($pattern, $item->item_key)) {
                    $item->item_value = '***';
                }
                $result[$item->item_key] = $item->item_value;
            }
        }

        return $result;
    }

    public function updateSectionSettings($section, $settings)
    {
        $settingsRepo = new SettingRepo();

        foreach ($settings as $key => $value) {
            $item = $settingsRepo->findItem($section, $key);
            if ($item) {
                $item->item_value = trim($value);
                $item->update();
            }
        }

        $cache = new SettingCache();

        $cache->rebuild($section);
    }

    public function updateStorageSettings($section, $settings)
    {
        $protocol = ['http://', 'https://'];

        if (isset($settings['domain'])) {
            $settings['domain'] = str_replace($protocol, '', $settings['domain']);
        }

        $this->updateSectionSettings($section, $settings);
    }

    public function updateVodSettings($section, $settings)
    {
        $this->updateSectionSettings($section, $settings);
    }

    public function updateLiveSettings($section, $settings)
    {
        $protocol = ['http://', 'https://'];

        if (in_array($section, ['live.push', 'live.pull'])) {
            if (isset($settings['domain'])) {
                $settings['domain'] = str_replace($protocol, '', $settings['domain']);
            }
        }

        $this->updateSectionSettings($section, $settings);
    }

    public function updateSmserSettings($section, $settings)
    {
        $template = $settings['template'];

        $keys = array_keys($template['id']);

        $myTemplate = [];

        foreach ($keys as $key) {
            $myTemplate[$key] = [
                'id' => $template['id'][$key],
                'content' => $template['content'][$key],
            ];
        }

        $settings['template'] = kg_json_encode($myTemplate);

        $this->updateSectionSettings($section, $settings);
    }

    public function updateVipSettings($items)
    {
        $vipRepo = new VipRepo();

        foreach ($items as $id => $price) {
            $vip = $vipRepo->findById($id);
            $vip->price = $price;
            $vip->update();
        }
    }

}
