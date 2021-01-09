<?php

namespace App\Http\Home\Services;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Repos\User as UserRepo;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Wechat as WechatService;
use EasyWeChat\Kernel\Messages\Text as TextMessage;

class WechatOfficialAccount extends Service
{

    public function getOfficialAccount()
    {
        $service = new WechatService();

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

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($user->id);

        $status = 0;

        if ($subscribe) {
            $status = $subscribe->deleted == 0 ? 1 : 0;
        }

        return $status;
    }

    public function handleNotify($message)
    {
        $service = new WechatService();

        $service->logger->info('Received Message ' . json_encode($message));

        switch ($message['MsgType']) {
            case 'event':
                switch ($message['Event']) {
                    case 'subscribe':
                        return $this->handleSubscribeEvent($message);
                        break;
                    case 'unsubscribe':
                        return $this->handleUnsubscribeEvent($message);
                        break;
                    case 'SCAN':
                        return $this->handleScanEvent($message);
                        break;
                    case 'CLICK':
                        return $this->handleClickEvent($message);
                        break;
                    case 'VIEW':
                        return $this->handleViewEvent($message);
                        break;
                    case 'LOCATION':
                        return $this->handleLocationEvent($message);
                        break;
                    default:
                        return $this->noMatchReply();
                        break;
                }
                break;
            case 'text':
                return $this->handleTextReply($message);
                break;
            case 'image':
                return $this->handleImageReply($message);
                break;
            case 'voice':
                return $this->handleVoiceReply($message);
                break;
            case 'video':
                return $this->handleVideoReply($message);
                break;
            case 'shortvideo':
                return $this->handleShortVideoReply($message);
                break;
            case 'location':
                return $this->handleLocationReply($message);
                break;
            case 'link':
                return $this->handleLinkReply($message);
                break;
            default:
                return $this->noMatchReply();
                break;
        }
    }

    protected function handleSubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe && $subscribe->deleted == 1) {
            $subscribe->deleted = 0;
            $subscribe->update();
        }

        return new TextMessage('开心呀，我们又多了一个小伙伴!');
    }

    protected function handleUnsubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe && $subscribe->deleted == 0) {
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

        $userRepo = new UserRepo();

        $user = $userRepo->findById($userId);

        if (!$user) return;

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe) {
            if ($subscribe->user_id != $userId) {
                $subscribe->user_id = $userId;
            }
            if ($subscribe->deleted == 1) {
                $subscribe->deleted = 0;
            }
            $subscribe->update();
        } else {
            $subscribe = new WechatSubscribeModel();
            $subscribe->user_id = $userId;
            $subscribe->open_id = $openId;
            $subscribe->create();
        }
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

}
