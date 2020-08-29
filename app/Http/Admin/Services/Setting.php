<?php

namespace App\Http\Admin\Services;

use App\Caches\Setting as SettingCache;
use App\Repos\Setting as SettingRepo;
use App\Repos\Vip as VipRepo;

class Setting extends Service
{

    public function getSectionSettings($section)
    {
        $settingsRepo = new SettingRepo();

        $items = $settingsRepo->findBySection($section);

        $result = new \stdClass();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $result->{$item->item_key} = $item->item_value;
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

        if (isset($settings['bucket_domain'])) {
            $settings['bucket_domain'] = str_replace($protocol, '', $settings['bucket_domain']);
        }

        if (isset($settings['ci_domain'])) {
            $settings['ci_domain'] = str_replace($protocol, '', $settings['ci_domain']);
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

        if (isset($settings['push_domain'])) {
            $settings['push_domain'] = str_replace($protocol, '', $settings['push_domain']);
        }

        if (isset($settings['pull_domain'])) {
            $settings['pull_domain'] = str_replace($protocol, '', $settings['pull_domain']);
        }

        if (isset($settings['ptt'])) {

            $ptt = $settings['ptt'];
            $keys = array_keys($ptt['id']);
            $myPtt = [];

            foreach ($keys as $key) {
                $myPtt[$key] = [
                    'id' => $ptt['id'][$key],
                    'bit_rate' => $ptt['bit_rate'][$key],
                    'summary' => $ptt['summary'][$key],
                    'height' => $ptt['height'][$key],
                ];
            }

            $settings['pull_trans_template'] = kg_json_encode($myPtt);
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

    public function getVipSettings()
    {
        $vipRepo = new VipRepo();

        return $vipRepo->findAll(['deleted' => 0]);
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

    public function getLiveSettings()
    {
        $live = $this->getSectionSettings('live');

        $live->notify_stream_begin_url = $live->notify_stream_begin_url ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'streamBegin']);
        $live->notify_stream_end_url = $live->notify_stream_end_url ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'streamEnd']);
        $live->notify_record_url = $live->notify_record_url ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'record']);
        $live->notify_snapshot_url = $live->notify_snapshot_url ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'snapshot']);
        $live->notify_porn_url = $live->notify_porn_url ?: kg_full_url(['for' => 'desktop.live_notify'], ['action' => 'porn']);

        return $live;
    }

}
