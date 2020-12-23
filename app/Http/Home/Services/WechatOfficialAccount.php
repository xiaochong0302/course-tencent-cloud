<?php

namespace App\Http\Home\Services;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Wechat as WechatService;
use App\Validators\User as UserValidator;
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

        $service->logger->debug('Received Message ' . json_encode($message));

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
                        return $this->emptyReplyMessage();
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
                return $this->emptyReplyMessage();
                break;
        }
    }

    protected function handleSubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';
        $eventKey = $message['EventKey'] ?? '';

        if (!$eventKey) {
            return $this->emptyReplyMessage();
        }

        $userId = str_replace('qrscene_', '', $eventKey);

        $this->handleSubscribeRelation($userId, $openId);

        return new TextMessage('开心呀，我们又多了一个小伙伴!');
    }

    protected function handleUnsubscribeEvent($message)
    {
        $openId = $message['FromUserName'] ?? '';

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe) {
            $subscribe->deleted = 1;
            $subscribe->update();
        }

        return new TextMessage('伤心呀，我们又少了一个小伙伴!');
    }

    protected function handleScanEvent($message)
    {
        /**
         * 注意:当已关注过用户扫码时,"EventKey"没有带"qrscene_"前缀
         */
        $openId = $message['FromUserName'] ?? '';
        $eventKey = $message['EventKey'] ?? '';
        $userId = $eventKey;

        $this->handleSubscribeRelation($userId, $openId);
    }

    protected function handleClickEvent($message)
    {
        $this->defaultReplyMessage();
    }

    protected function handleViewEvent($message)
    {
        $this->defaultReplyMessage();
    }

    protected function handleLocationEvent($message)
    {
        $this->defaultReplyMessage();
    }

    protected function handleTextReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function handleImageReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function handleVoiceReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function handleVideoReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function handleShortVideoReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function handleLocationReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function handleLinkReply($message)
    {
        return $this->defaultReplyMessage();
    }

    protected function emptyReplyMessage()
    {
        return new TextMessage('');
    }

    protected function defaultReplyMessage()
    {
        return new TextMessage('没有匹配的服务，如有需要请联系客服！');
    }

    protected function handleSubscribeRelation($userId, $openId)
    {
        $validator = new UserValidator();

        $validator->checkUser($userId);

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByOpenId($openId);

        if ($subscribe) {
            if ($subscribe->deleted == 1) {
                $subscribe->deleted = 0;
                $subscribe->update();
            }
        } else {
            $subscribe = $subscribeRepo->findSubscribe($userId, $openId);
            if (!$subscribe) {
                $subscribe = new WechatSubscribeModel();
                $subscribe->user_id = $userId;
                $subscribe->open_id = $openId;
                $subscribe->create();
            }
        }
    }

}
