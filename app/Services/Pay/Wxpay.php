<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Pay;

use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Models\Withdraw as WithdrawModel;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\Withdraw as WithdrawRepo;
use App\Repos\WithdrawAccount as WithdrawAccountRepo;
use App\Services\Pay as PayService;
use Psr\Http\Message\ResponseInterface;
use Yansongda\Artful\Exception\InvalidResponseException;
use Yansongda\Artful\Logger;
use Yansongda\Pay\Config\WechatConfig;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Wechat as WechatProvider;
use Yansongda\Pay\Traits\WechatTrait;
use Yansongda\Supports\Collection;

class Wxpay extends PayService
{

    use WechatTrait;

    /**
     * @var WechatProvider
     */
    protected WechatProvider $provider;

    public function __construct()
    {
        parent::__construct();

        $this->provider = Pay::wechat();
    }

    /**
     * 扫码支付
     *
     * @param TradeModel $trade
     * @return string|bool
     */
    public function scan(TradeModel $trade): string|bool
    {
        try {

            $response = $this->provider->scan([
                'out_trade_no' => $trade->sn,
                'time_expire' => $this->getTimeExpire(),
                'description' => $trade->subject,
                'amount' => [
                    'total' => 100 * $trade->amount,
                ],
            ]);

            $result = $response['code_url'] ?? false;

        } catch (\Exception $e) {

            Logger::error('Wxpay Scan Error: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * h5支付
     *
     * @param TradeModel $trade
     * @return string|false
     */
    public function h5(TradeModel $trade): string|false
    {
        try {

            $response = $this->provider->h5([
                'out_trade_no' => $trade->sn,
                'time_expire' => $this->getTimeExpire(),
                'description' => $trade->subject,
                'amount' => [
                    'total' => 100 * $trade->amount,
                ],
                'scene_info' => [
                    'payer_client_ip' => $this->request->getClientAddress(),
                    'h5_info' => [
                        'type' => 'Wap',
                    ]
                ],
            ]);

            $result = $response['h5_url'] ?? false;

        } catch (\Exception $e) {

            Logger::error('Wxpay H5 Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 公众号支付
     *
     * @param TradeModel $trade
     * @param string $openId
     * @return Collection|bool
     */
    public function mp(TradeModel $trade, string $openId): Collection|bool
    {
        try {

            $result = $this->provider->mp([
                'out_trade_no' => $trade->sn,
                'time_expire' => $this->getTimeExpire(),
                'description' => $trade->subject,
                'amount' => [
                    'total' => 100 * $trade->amount,
                ],
                'payer' => [
                    'openid' => $openId,
                ]
            ]);

        } catch (\Exception $e) {

            Logger::error('Wxpay mp Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 小程序支付
     *
     * @param TradeModel $trade
     * @param string $openId
     * @return Collection|bool
     */
    public function mini(TradeModel $trade, string $openId): Collection|bool
    {
        try {

            $result = $this->provider->mini([
                'out_trade_no' => $trade->sn,
                'time_expire' => $this->getTimeExpire(),
                'description' => $trade->subject,
                'amount' => [
                    'total' => 100 * $trade->amount,
                ],
                'payer' => [
                    'openid' => $openId,
                ],
            ]);

        } catch (\Exception $e) {

            Logger::error('Wxpay mini Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 异步通知
     *
     * @return ResponseInterface|false
     */
    public function callback(): ResponseInterface|false
    {
        try {

            $data = $this->provider->callback();

            Logger::info('Wxpay Callback Data: ', $data->all());

        } catch (\Exception $e) {

            Logger::error('Wxpay Callback Error: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        $eventType = $data['event_type'] ?? null;

        $result = false;

        if ($eventType == 'TRANSACTION.SUCCESS') {
            $result = $this->handleTradeCallback($data);
        } elseif ($eventType == 'REFUND.SUCCESS') {
            $result = $this->handleRefundCallback($data);
        } elseif ($eventType == 'MCHTRANSFER.BILL.FINISHED') {
            $result = $this->handleWithdrawCallback($data);
        }

        return $result;
    }

    /**
     * 查询订单
     */
    public function query(array $order): Collection|bool
    {
        try {

            $result = $this->provider->query($order);

        } catch (\Exception $e) {

            Logger::error('Wxpay Query Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 查询交易
     */
    public function queryTrade(TradeModel $trade): Collection|bool
    {
        return $this->query([
            'out_trade_no' => $trade->sn,
            '_action' => $trade->scene,
        ]);
    }

    /**
     * 查询退款
     */
    public function queryRefund(RefundModel $refund): Collection|bool
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($refund->trade_id);

        return $this->query([
            'out_refund_no' => $refund->sn,
            '_action' => "refund_{$trade->scene}",
        ]);
    }

    /**
     * 查询提现
     */
    public function queryWithdraw(WithdrawModel $withdraw): Collection|bool
    {
        return $this->query([
            'out_bill_no' => $withdraw->sn,
            '_action' => 'transfer',
        ]);
    }

    /**
     * 交易是否成功
     */
    public function isTradeSuccess(TradeModel $trade): bool
    {
        $result = false;

        $response = $this->queryTrade($trade);

        if ($response && isset($response['trade_state'])) {
            $result = $response['trade_state'] == 'SUCCESS';
        }

        return $result;
    }

    /**
     * 退款是否成功
     */
    public function isRefundSuccess(RefundModel $refund): bool
    {
        $result = false;

        $response = $this->queryRefund($refund);

        if ($response && isset($response['status'])) {
            $result = $response['status'] == 'SUCCESS';
        }

        return $result;
    }

    /**
     * 提现是否成功
     */
    public function isWithdrawSuccess(WithdrawModel $withdraw): bool
    {
        $result = false;

        $response = $this->queryWithdraw($withdraw);

        if ($response && isset($response['state'])) {
            $result = $response['state'] == 'SUCCESS';
        }

        return $result;
    }

    /**
     * 关闭交易
     */
    public function close(TradeModel $trade): Collection|false
    {
        try {

            $order = [
                'out_trade_no' => $trade->sn,
                '_action' => $trade->scene,
            ];

            $result = $this->provider->close($order);

            Logger::info('Wxpay Close Data: ', $result->all());

        } catch (\Exception $e) {

            Logger::error('Wxpay Close Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 申请退款
     *
     * @param RefundModel $refund
     * @return bool
     */
    public function refund(RefundModel $refund): bool
    {
        try {

            $tradeRepo = new TradeRepo();

            $trade = $tradeRepo->findById($refund->trade_id);

            $response = $this->provider->refund([
                'out_trade_no' => $trade->sn,
                'out_refund_no' => $refund->sn,
                'amount' => [
                    'total' => 100 * $trade->amount,
                    'refund' => 100 * $refund->amount,
                    'currency' => 'CNY',
                ],
            ]);

            $result = !empty($response['refund_id']);

            Logger::info('Wxpay Refund Data: ', $response->all());

        } catch (\Exception $e) {

            /**
             * 获取原始异常信息，方便管理后台直接查看错误
             */
            if ($e instanceof InvalidResponseException) {

                $errorNote = [
                    'code' => $e->response['code'] ?? '',
                    'message' => $e->response['message'] ?? '',
                ];

                Logger::info('Wxpay Original Refund Exception: ', $errorNote);

            } else {

                $errorNote = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ];
            }

            $refund->error_note = kg_json_encode($errorNote);
            $refund->update();

            Logger::error('Wxpay Refund Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 申请提现
     */
    public function withdraw(WithdrawModel $withdraw): bool
    {
        try {

            $accountRepo = new WithdrawAccountRepo();

            $account = $accountRepo->findById($withdraw->account_id);

            /**
             * 转账场景（佣金报酬）说明
             * @link https://pay.weixin.qq.com/doc/v3/merchant/4013774590
             */
            $response = $this->provider->transfer([
                'out_bill_no' => $withdraw->sn,
                'openid' => $account->identity,
                'transfer_amount' => 100 * $withdraw->trans_amount,
                'transfer_remark' => '分销提现',
                'transfer_scene_id' => '1005', // 固定场景ID
                'transfer_scene_report_infos' => [
                    [
                        'info_type' => '岗位类型',
                        'info_content' => '网络营销人员',
                    ],
                    [
                        'info_type' => '报酬说明',
                        'info_content' => sprintf('%s佣金提现', date('y年m月', $withdraw->create_time)),
                    ],
                ],
            ]);

            $result = !empty($response['transfer_bill_no']);

            if ($response['state'] == 'WAIT_USER_CONFIRM') {

                $withdraw->status = WithdrawModel::STATUS_WAIT_CONFIRM;
                $withdraw->update();

                /**
                 * @var WechatConfig $config
                 */
                $config = self::getProviderConfig('wechat');

                $appType = $this->getAppTypeByScene($account->scene);

                $content = [
                    'app_id' => $config->getAppIdByType($appType),
                    'mch_id' => $config->getMchId(),
                    'package_info' => $response['package_info'],
                ];

                $cache = $this->getCache();
                $cacheKey = $this->getWithdrawConfirmCacheKey($withdraw->id);
                $cache->set($cacheKey, $content, 86400);
            }

            Logger::info('Wxpay Transfer Data: ', $response->all());

        } catch (\Exception $e) {

            /**
             * 获取原始异常信息，方便管理后台直接查看错误
             */
            if ($e instanceof InvalidResponseException) {

                $errorNote = [
                    'code' => $e->response['code'] ?? '',
                    'message' => $e->response['message'] ?? '',
                ];

                Logger::info('Wxpay Original Transfer Exception: ', $errorNote);

            } else {

                $errorNote = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ];
            }

            $withdraw->error_note = kg_json_encode($errorNote);
            $withdraw->update();

            Logger::error('Wxpay Transfer Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 取消提现（撤销商家转账）
     */
    public function cancelWithdraw(WithdrawModel $withdraw): bool
    {
        try {

            $order = [
                'out_bill_no' => $withdraw->sn,
                '_action' => 'mch_transfer',
            ];

            $response = $this->provider->cancel($order);

            $result = $response['state'] == 'CANCELLED';

        } catch (\Exception $e) {

            Logger::error('Wxpay Cancel Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $result = false;
        }

        return $result;
    }

    /**
     * 获取提现确认参数
     */
    public function getWithdrawConfirmParams(int $withdrawId): array
    {
        $cache = $this->getCache();

        $cacheKey = $this->getWithdrawConfirmCacheKey($withdrawId);

        return $cache->get($cacheKey);
    }

    /**
     * 删除提现确认缓存
     */
    public function deleteWithdrawConfirmCache(int $withdrawId): void
    {
        $cache = $this->getCache();

        $cacheKey = $this->getWithdrawConfirmCacheKey($withdrawId);

        $cache->delete($cacheKey);
    }

    /**
     * 处理交易回调
     */
    protected function handleTradeCallback(Collection $data): ResponseInterface|false
    {
        $ciphertext = $data['resource']['ciphertext'] ?? null;

        if (!$ciphertext) return false;

        if ($ciphertext['trade_state'] != 'SUCCESS') {
            return false;
        }

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($ciphertext['out_trade_no']);

        if (!$trade) return false;

        /**
         * 注意浮点数精度丢失问题
         */
        $amount = intval(strval(100 * $trade->amount));

        if ($ciphertext['amount']['total'] != $amount) {
            return false;
        }

        if ($trade->status == TradeModel::STATUS_FINISHED) {
            return $this->provider->success();
        }

        if ($trade->status != TradeModel::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $ciphertext['transaction_id'] ?: '';
        $trade->channel_identity = $ciphertext['payer']['openid'] ?: '';
        $trade->update();

        $this->eventsManager->fire('Trade:afterSuccess', $this, $trade);

        return $this->provider->success();
    }

    /**
     * 处理退款回调
     */
    protected function handleRefundCallback(Collection $data): ResponseInterface|false
    {
        $ciphertext = $data['resource']['ciphertext'] ?? null;

        if (!$ciphertext) return false;

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findBySn($ciphertext['out_refund_no']);

        if (!$refund) return false;

        if (in_array($ciphertext['refund_status'], ['CLOSED', 'ABNORMAL'])) {

            $this->eventsManager->fire('Refund:afterFail', $this, $refund);

            return $this->provider->success();
        }

        if ($ciphertext['refund_status'] == 'SUCCESS') {

            /**
             * 注意浮点数精度丢失问题
             */
            $amount = intval(strval(100 * $refund->amount));

            if ($ciphertext['amount']['total'] != $amount) {
                return false;
            }

            if ($refund->status == RefundModel::STATUS_FINISHED) {
                return $this->provider->success();
            }

            $this->eventsManager->fire('Refund:afterSuccess', $this, $refund);

            return $this->provider->success();
        }

        return $this->provider->success();
    }

    /**
     * 处理提现回调
     */
    protected function handleWithdrawCallback(Collection $data): ResponseInterface|false
    {
        $ciphertext = $data['resource']['ciphertext'] ?? null;

        if (!$ciphertext) return false;

        $withdrawRepo = new WithdrawRepo();

        $withdraw = $withdrawRepo->findBySn($ciphertext['out_bill_no']);

        if (!$withdraw) return false;

        if (in_array($ciphertext['state'], ['FAIL', 'CANCELLED'])) {

            $this->eventsManager->fire('Withdraw:afterFail', $this, $withdraw);

            return $this->provider->success();
        }

        if ($ciphertext['state'] == 'SUCCESS') {

            /**
             * 注意浮点数精度丢失问题
             */
            $amount = intval(strval(100 * $withdraw->trans_amount));

            if ($ciphertext['transfer_amount'] != $amount) {
                return false;
            }

            if ($withdraw->status == WithdrawModel::STATUS_FINISHED) {
                return $this->provider->success();
            }

            $this->eventsManager->fire('Withdraw:afterSuccess', $this, $withdraw);

            return $this->provider->success();
        }

        return $this->provider->success();
    }

    /**
     * 获取订单失效时间
     */
    protected function getTimeExpire(): string
    {
        $lifetime = kg_config('trade.lifetime', 15 * 60);

        return date('Y-m-d\TH:i:sP', time() + $lifetime);
    }

    /**
     * 通过场景获取App类型
     */
    protected function getAppTypeByScene(string $scene): string
    {
        return match ($scene) {
            'app' => 'app',
            'mini' => 'mini',
            default => 'mp',
        };
    }

    /**
     * 获取提现确认缓存Key
     */
    protected function getWithdrawConfirmCacheKey(int $withdrawId): string
    {
        return "withdraw-confirm-{$withdrawId}";
    }

}
