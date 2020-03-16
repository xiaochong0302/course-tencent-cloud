<?php

namespace App\Http\Admin\Services;

use App\Repos\Config as ConfigRepo;
use App\Repos\Vip as VipRepo;

class Config extends Service
{

    public function getSectionConfig($section)
    {
        $configRepo = new ConfigRepo();

        $items = $configRepo->findBySection($section);

        $result = new \stdClass();

        if ($items->count() > 0) {
            foreach ($items as $item) {
                $result->{$item->item_key} = $item->item_value;
            }
        }

        return $result;
    }

    public function updateSectionConfig($section, $config)
    {
        $configRepo = new ConfigRepo();

        foreach ($config as $key => $value) {
            $item = $configRepo->findItem($section, $key);
            if ($item) {
                $item->item_value = trim($value);
                $item->update();
            }
        }
    }

    public function updateStorageConfig($section, $config)
    {
        $protocol = ['http://', 'https://'];

        if (isset($config['bucket_domain'])) {
            $config['bucket_domain'] = str_replace($protocol, '', $config['bucket_domain']);
        }

        if (isset($config['ci_domain'])) {
            $config['ci_domain'] = str_replace($protocol, '', $config['ci_domain']);
        }

        $this->updateSectionConfig($section, $config);
    }

    public function updateVodConfig($section, $config)
    {
        $this->updateSectionConfig($section, $config);
    }

    public function updateLiveConfig($section, $config)
    {
        $protocol = ['http://', 'https://'];

        if (isset($config['push_domain'])) {
            $config['push_domain'] = str_replace($protocol, '', $config['push_domain']);
        }

        if (isset($config['pull_domain'])) {
            $config['pull_domain'] = str_replace($protocol, '', $config['pull_domain']);
        }

        if (isset($config['ptt'])) {

            $ptt = $config['ptt'];
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

            $config['pull_trans_template'] = kg_json_encode($myPtt);
        }

        $this->updateSectionConfig($section, $config);
    }

    public function updateSmserConfig($section, $config)
    {
        $template = $config['template'];
        $keys = array_keys($template['id']);
        $myTemplate = [];

        foreach ($keys as $key) {
            $myTemplate[$key] = [
                'id' => $template['id'][$key],
                'content' => $template['content'][$key],
            ];
        }

        $config['template'] = kg_json_encode($myTemplate);

        $this->updateSectionConfig($section, $config);
    }

    public function getVipConfig()
    {
        $vipRepo = new VipRepo();

        $config = $vipRepo->findAll(['deleted' => 0]);

        return $config;
    }

    public function updateVipConfig($items)
    {
        $vipRepo = new VipRepo();

        foreach ($items as $id => $price) {
            $vip = $vipRepo->findById($id);
            $vip->price = $price;
            $vip->update();
        }
    }

}
