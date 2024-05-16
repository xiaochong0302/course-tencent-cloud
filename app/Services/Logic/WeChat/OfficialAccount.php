<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\WeChat;

use App\Models\Connect as ConnectModel;
use App\Repos\Connect as ConnectRepo;
use App\Services\Service as AppService;
use App\Services\WeChat as WeChatService;
use EasyWeChat\Kernel\Messages\Text as TextMessage;
use Phalcon\Text;

class OfficialAccount extends AppService
{

    /**
     * 二维码场景类型
     */
    const QR_SCENE_LOGIN = 'login';
    const QR_SCENE_SUBSCRIBE = 'subscribe';

    public function getOfficialAccount()
    {
        $service = new WeChatService();

        return $service->getOfficialAccount();
    }

    public function createSubscribeQrCode()
    {
        $user = $this->getLoginUser();

        $app = $this->getOfficialAccount();

        $sceneValue = sprintf('%s%s', self::QR_SCENE_SUBSCRIBE, $user->id);

        $result = $app->qrcode->temporary($sceneValue);

        $url = $app->qrcode->url($result['ticket']);

        return ['url' => $url];
    }

    public function createLoginQrCode()
    {
        $app = $this->getOfficialAccount();

        $ticket = Text::random(0, 16);

        $sceneValue = sprintf('%s%s', self::QR_SCENE_LOGIN, $ticket);

        $result = $app->qrcode->temporary($sceneValue);

        $keyName = $this->getLoginCacheKey($ticket);

        $content = [
            'action' => '',
            'open_id' => '',
        ];

        $this->getCache()->save($keyName, $content, 30 * 60);

        $url = $app->qrcode->url($result['ticket']);

        return [
            'ticket' => $ticket,
            'url' => $url,
        ];
    }

    public function getLoginStatus($ticket)
    {
        $keyName = $this->getLoginCacheKey($ticket);

        return $this->getCache()->get($keyName);
    }

    public function getSubscribeStatus()
    {
        $user = $this->getLoginUser();

        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByUserId($user->id, ConnectModel::PROVIDER_WECHAT_OA);

        return $connect ? 1 : 0;
    }

    public function getLoginOpenId($ticket)
    {
        $keyName = $this->getLoginCacheKey($ticket);

        $content = $this->getCache()->get($keyName);

        return $content['open_id'] ?? null;
    }

    public function getLoginCacheKey($key)
    {
        return "wechat_oa_login:{$key}";
    }

    public function handleNotify($message)
    {
        $logger = $this->getWeChatLogger();

        $logger->debug('Received Message: ' . json_encode($message));

        switch ($message['MsgType']) {
            case 'event':
                switch ($message['Event']) {
                    case 'subscribe':
                        return $this->handleSubscribeEvent($message);
                    case 'unsubscribe':
                        return $this->handleUnsubscribeEvent($message);
                    case 'SCAN':
                        return $this->handleScanEvent($message);
                    case 'CLICK':
                        return $this->handleClickEvent($message);
                    case 'VIEW':
                        return $this->handleViewEvent($message);
                    case 'LOCATION':
                        return $this->handleLocationEvent($message);
                    default:
                        return $this->noMatchReply();
                }
            case 'text':
                return $this->handleTextReply($message);
            case 'image':
                return $this->handleImageReply($message);
            case 'voice':
                return $this->handleVoiceReply($message);
            case 'video':
                return $this->handleVideoReply($message);
            case 'shortvideo':
                return $this->handleShortVideoReply($message);
            case 'location':
                return $this->handleLocationReply($message);
            case 'link':
                return $this->handleLinkReply($message);
            default:
                return $this->noMatchReply();
        }
    }

    protected function handleSubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';

        if (empty($openId)) return null;

        return new TextMessage('开心呀，我们又多了一个小伙伴!');
    }

    protected function handleUnsubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';

        if (empty($openId)) return null;

        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openId, ConnectModel::PROVIDER_WECHAT_OA);

        if ($connect) {
            $connect->deleted = 1;
            $connect->update();
        }

        return new TextMessage('伤心呀，我们又少了一个小伙伴!');
    }

    protected function handleScanEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';
        $eventKey = $message['EventKey'] ?? '';

        if (Text::startsWith($eventKey, self::QR_SCENE_LOGIN)) {
            return $this->handleLoginScanEvent($eventKey, $openId);
        } elseif (Text::startsWith($eventKey, self::QR_SCENE_SUBSCRIBE)) {
            return $this->handleSubscribeScanEvent($eventKey, $openId);
        }

        return $this->emptyReply();
    }

    protected function handleLoginScanEvent($eventKey, $openId)
    {
        $ticket = str_replace(self::QR_SCENE_LOGIN, '', $eventKey);

        if (empty($ticket) || empty($openId)) return null;

        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openId, ConnectModel::PROVIDER_WECHAT_OA);

        $keyName = $this->getLoginCacheKey($ticket);

        $cache = $this->getCache();

        $content = [
            'action' => $connect ? 'login' : 'bind',
            'open_id' => $openId,
        ];

        $cache->save($keyName, $content, 30 * 60);

        return $this->emptyReply();
    }

    protected function handleSubscribeScanEvent($eventKey, $openId)
    {
        $userId = str_replace(self::QR_SCENE_SUBSCRIBE, '', $eventKey);

        if (empty($userId) || empty($openId)) return null;

        $userInfo = $this->getUserInfo($openId);

        $unionId = $userInfo['unionid'] ?: '';

        $connectRepo = new ConnectRepo();

        $connect = $connectRepo->findByOpenId($openId, ConnectModel::PROVIDER_WECHAT_OA);

        if ($connect) return null;

        $connect = new ConnectModel();

        $connect->user_id = $userId;
        $connect->open_id = $openId;
        $connect->union_id = $unionId;
        $connect->provider = ConnectModel::PROVIDER_WECHAT_OA;

        $connect->create();

        return $this->emptyReply();
    }

    protected function handleClickEvent($message)
    {
        return $this->emptyReply();
    }

    protected function handleViewEvent($message)
    {
        return $this->emptyReply();
    }

    protected function handleLocationEvent($message)
    {
        return $this->emptyReply();
    }

    protected function handleTextReply($message)
    {
        return $this->emptyReply();
    }

    protected function handleImageReply($message)
    {
        return $this->emptyReply();
    }

    protected function handleVoiceReply($message)
    {
        return $this->emptyReply();
    }

    protected function handleVideoReply($message)
    {
        return $this->emptyReply();
    }

    protected function handleShortVideoReply($message)
    {
        return $this->emptyReply();
    }

    protected function handleLocationReply($message)
    {
        return $this->emptyReply();
    }

    protected function handleLinkReply($message)
    {
        return $this->emptyReply();
    }

    protected function emptyReply()
    {
        return null;
    }

    protected function noMatchReply()
    {
        return new TextMessage('没有匹配的服务哦！');
    }

    protected function getUserInfo($openId)
    {
        $app = $this->getOfficialAccount();

        return $app->user->get($openId);
    }

    protected function getWeChatLogger()
    {
        $service = new WeChatService();

        return $service->logger;
    }

}
