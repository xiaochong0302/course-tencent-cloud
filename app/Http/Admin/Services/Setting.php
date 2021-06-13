<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\Setting as SettingCache;
use App\Repos\Setting as SettingRepo;
use App\Repos\Vip as VipRepo;
use App\Services\WeChat as WeChatService;

class Setting extends Service
{

    public function getQQAuthSettings()
    {
        $oauth = $this->getSettings('oauth.qq');

        $oauth['redirect_uri'] = $oauth['redirect_uri'] ?: kg_full_url(['for' => 'home.oauth.qq_callback']);

        return $oauth;
    }

    public function getWeixinAuthSettings()
    {
        $oauth = $this->getSettings('oauth.weixin');

        $oauth['redirect_uri'] = $oauth['redirect_uri'] ?: kg_full_url(['for' => 'home.oauth.weixin_callback']);

        return $oauth;
    }

    public function getWeiboAuthSettings()
    {
        $oauth = $this->getSettings('oauth.weibo');

        $oauth['redirect_uri'] = $oauth['redirect_uri'] ?: kg_full_url(['for' => 'home.oauth.weibo_callback']);
        $oauth['refuse_uri'] = $oauth['refuse_uri'] ?: kg_full_url(['for' => 'home.oauth.weibo_refuse']);

        return $oauth;
    }

    public function getAlipaySettings()
    {
        $alipay = $this->getSettings('pay.alipay');

        $alipay['return_url'] = $alipay['return_url'] ?: kg_full_url(['for' => 'home.alipay_callback']);
        $alipay['notify_url'] = $alipay['notify_url'] ?: kg_full_url(['for' => 'home.alipay_notify']);

        return $alipay;
    }

    public function getWxpaySettings()
    {
        $wxpay = $this->getSettings('pay.wxpay');

        $wxpay['return_url'] = $wxpay['return_url'] ?: kg_full_url(['for' => 'home.wxpay_callback']);
        $wxpay['notify_url'] = $wxpay['notify_url'] ?: kg_full_url(['for' => 'home.wxpay_notify']);

        return $wxpay;
    }

    public function getWeChatOASettings()
    {
        $oa = $this->getSettings('wechat.oa');

        $oa['notify_url'] = $oa['notify_url'] ?: kg_full_url(['for' => 'home.wechat_oa.notify']);

        $oa['menu'] = json_decode($oa['menu'], true);

        /**
         * 构造一个3＊5的二维树形菜单
         */
        for ($i = 0; $i < 3; $i++) {
            if (!isset($oa['menu'][$i])) {
                $oa['menu'][$i] = ['name' => sprintf('菜单%s', $i + 1)];
            }
            for ($j = 0; $j < 5; $j++) {
                if (!isset($oa['menu'][$i]['children'][$j])) {
                    $oa['menu'][$i]['children'][$j] = [
                        'type' => 'view',
                        'name' => '',
                        'url' => '',
                    ];;
                }
            }
        }

        return $oa;
    }

    public function getVipSettings()
    {
        $vipRepo = new VipRepo();

        return $vipRepo->findAll();
    }

    public function getLiveSettings($section)
    {
        $result = $this->getSettings($section);

        if ($section == 'live.notify') {
            $result['stream_begin_url'] = $result['stream_begin_url'] ?: kg_full_url(['for' => 'home.live_notify'], ['action' => 'streamBegin']);
            $result['stream_end_url'] = $result['stream_end_url'] ?: kg_full_url(['for' => 'home.live_notify'], ['action' => 'streamEnd']);
            $result['record_url'] = $result['record_url'] ?: kg_full_url(['for' => 'home.live_notify'], ['action' => 'record']);
            $result['snapshot_url'] = $result['snapshot_url'] ?: kg_full_url(['for' => 'home.live_notify'], ['action' => 'snapshot']);
            $result['porn_url'] = $result['porn_url'] ?: kg_full_url(['for' => 'home.live_notify'], ['action' => 'porn']);
        }

        return $result;
    }

    public function getSettings($section)
    {
        $settingsRepo = new SettingRepo();

        $items = $settingsRepo->findBySection($section);

        $result = [];

        /**
         * demo分支过滤敏感数据，100001帐号除外
         */
        if ($items->count() > 0) {
            $pattern = '/(id|auth|key|secret|token|password|pwd|mobile|phone|mail|email)/';
            $controllerName = $this->dispatcher->getControllerName();
            foreach ($items as $item) {
                $case1 = preg_match($pattern, $item->item_key);
                $case2 = $controllerName == 'setting';
                $case3 = $this->getLoginUser()->id != 100001;
                if ($case1 && $case2 && $case3) {
                    $item->item_value = '******';
                }
                $result[$item->item_key] = $item->item_value;
            }
        }

        return $result;
    }

    public function updateSettings($section, $settings)
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

        $this->updateSettings($section, $settings);
    }

    public function updateVodSettings($section, $settings)
    {
        $this->updateSettings($section, $settings);
    }

    public function updateLiveSettings($section, $settings)
    {
        $protocol = ['http://', 'https://'];

        if (in_array($section, ['live.push', 'live.pull'])) {
            if (isset($settings['domain'])) {
                $settings['domain'] = str_replace($protocol, '', $settings['domain']);
            }
        }

        $this->updateSettings($section, $settings);
    }

    public function updateSmsSettings($section, $settings)
    {
        if (isset($settings['template'])) {
            $settings['template'] = kg_json_encode($settings['template']);
        }

        $this->updateSettings($section, $settings);
    }

    public function updatePointSettings($section, $settings)
    {
        if (isset($settings['event_rule'])) {
            $settings['event_rule'] = kg_json_encode($settings['event_rule']);
        }

        if (isset($settings['consume_rule'])) {
            $settings['consume_rule'] = kg_json_encode($settings['consume_rule']);
        }

        $this->updateSettings($section, $settings);
    }

    public function updateVipSettings($items)
    {
        $vipRepo = new VipRepo();

        foreach ($items as $id => $item) {
            $vip = $vipRepo->findById($id);
            if (!$vip) continue;
            $vip->title = sprintf('%s个月', $item['expiry']);
            $vip->expiry = (int)$item['expiry'];
            $vip->price = (float)$item['price'];
            $vip->deleted = (int)$item['deleted'];
            $vip->update();
        }
    }

    public function updateWeChatOASettings($section, $settings)
    {
        if (!empty($settings['notice_template'])) {
            $settings['notice_template'] = kg_json_encode($settings['notice_template']);
        }

        $buttons = [];

        if (!empty($settings['menu'])) {
            foreach ($settings['menu'] as $i => $top) {
                $buttons[$i]['name'] = $top['name'];
                $buttons[$i]['url'] = $top['url'];
                $buttons[$i]['type'] = 'view';
                foreach ($top['children'] as $j => $sub) {
                    if (!empty($sub['name']) && !empty($sub['url'])) {
                        $buttons[$i]['sub_button'][$j]['name'] = $sub['name'];
                        $buttons[$i]['sub_button'][$j]['url'] = $sub['url'];
                        $buttons[$i]['sub_button'][$j]['type'] = 'view';
                    } else {
                        unset($settings['menu'][$i]['children'][$j]);
                    }
                }
            }
            $settings['menu'] = kg_json_encode($settings['menu']);
        }

        if (!empty($buttons)) {
            $service = new WeChatService();
            $oa = $service->getOfficialAccount();
            $oa->menu->create($buttons);
        }

        $this->updateSettings($section, $settings);
    }

}
