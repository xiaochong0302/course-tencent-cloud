<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\WeChatSubscribe as WeChatSubscribeModel;
use App\Repos\User as UserRepo;
use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Services\WeChat as WeChatService;
use EasyWeChat\Kernel\Messages\Text as TextMessage;

class WeChatOfficialAccount extends Service
{

    public function getOfficialAccount()
    {
        $service = new WeChatService();

        return $service->getOfficialAccount();
    }

    public function createSubscribeQrCode()
    {
        $user = $this->getLoginUser();

        $app = $this->getOfficialAccount();

        $result = $app->qrcode->temporary($user->id);

        return $app->qrcode->url($result['ticket']);
    }

    public function getSubscribeStatus()
    {
        $user = $this->getLoginUser();

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($user->id);

        return $subscribe ? 1 : 0;
    }

    public function handleNotify($message)
    {
        $service = new WeChatService();

        $service->logger->debug('Received Message ' . json_encode($message));

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
        $eventKey = $message['EventKey'] ?? '';

        /**
         * 带场景值的关注事件
         */
        $userId = str_replace('qrscene_', '', $eventKey);

        if ($userId && $openId) {
            $this->saveWechatSubscribe($userId, $openId);
        }

        return new TextMessage('开心呀，我们又多了一个小伙伴!');
    }

    protected function handleUnsubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe) {
            $subscribe->deleted = 1;
            $subscribe->update();
        }

        return new TextMessage('伤心呀，我们又少了一个小伙伴!');
    }

    protected function handleScanEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';
        $eventKey = $message['EventKey'] ?? '';

        $userId = str_replace('qrscene_', '', $eventKey);

        if ($userId && $openId) {
            $userInfo = $this->getUserInfo($openId);
            $unionId = $userInfo['unionid'] ?: '';
            $this->saveWechatSubscribe($userId, $openId, $unionId);
        }

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

    protected function saveWechatSubscribe($userId, $openId, $unionId = '')
    {
        if (!$userId || !$openId) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($userId);

        if (!$user) return;

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe) {
            if ($subscribe->user_id != $userId) {
                $subscribe->user_id = $userId;
            }
            if (empty($subscribe->union_id) && !empty($unionId)) {
                $subscribe->union_id = $unionId;
            }
            if ($subscribe->deleted == 1) {
                $subscribe->deleted = 0;
            }
            $subscribe->update();
        } else {
            $subscribe = new WeChatSubscribeModel();
            $subscribe->user_id = $userId;
            $subscribe->open_id = $openId;
            $subscribe->union_id = $unionId;
            $subscribe->create();
        }
    }

    protected function getUserInfo($openId)
    {
        $app = $this->getOfficialAccount();

        return $app->user->get($openId);
    }

    protected function getWechatLogger()
    {
        $service = new WeChatService();

        return $service->logger;
    }

}
