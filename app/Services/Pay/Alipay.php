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
use App\Repos\Trade as TradeRepo;
use App\Repos\WithdrawAccount as WithdrawAccountRepo;
use App\Services\Pay as PayService;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Yansongda\Artful\Logger;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Provider\Alipay as AlipayProvider;
use Yansongda\Supports\Collection;

class Alipay extends PayService
{

    /**
     * @var AlipayProvider
     */
    protected AlipayProvider $provider;

    public function __construct()
    {
        parent::__construct();

        $this->provider = Pay::alipay();
    }

    /**
     * 扫码支付
     */
    public function scan(TradeModel $trade): string|false
    {
        try {

            $response = $this->provider->scan([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'time_expire' => $this->getTimeExpire(),
                'subject' => $trade->subject,
            ]);

            $result = $response['qr_code'] ?? false;

            if ($response['code'] != '10000') {
                Logger::error('Alipay Scan Error: ', $response->all());
            }

        } catch (\Exception $e) {

            Logger::error('Alipay Scan Exception: ', [
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
     */
    public function h5(TradeModel $trade): string|false
    {
        try {

            /**
             * @var Response $response
             */
            $response = $this->provider->h5([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'time_expire' => $this->getTimeExpire(),
                'subject' => $trade->subject,
                '_method' => 'get',
            ]);

            $location = $response->getHeaderLine('Location');

            $result = $location ?: false;

        } catch (\Exception $e) {

            Logger::error('Alipay h5 Exception: ', [
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
     */
    public function mini(TradeModel $trade, string $buyerId): string|false
    {
        try {

            $response = $this->provider->mini([
                'out_trade_no' => $trade->sn,
                'total_amount' => $trade->amount,
                'time_expire' => $this->getTimeExpire(),
                'subject' => $trade->subject,
                'buyer_id' => $buyerId,
            ]);

            $result = $response['trade_no'] ?? false;

            if ($response['code'] != '10000') {
                Logger::error('Alipay Mini Error: ', $response->all());
            }

        } catch (\Exception $e) {

            Logger::error('Alipay mini Exception: ', [
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
     */
    public function callback(): ResponseInterface|bool
    {
        try {

            $data = $this->provider->callback();

            Logger::info('Alipay Callback Data: ', $data->all());

        } catch (\Exception $e) {

            Logger::error('Alipay Callback Exception: ', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        $result = false;

        if ($data['trade_status'] == 'TRADE_SUCCESS') {
            $result = $this->handleTradeCallback($data);
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

            if ($result['code'] != '10000') {
                Logger::error('Alipay Query Error: ', $result->all());
            }

        } catch (\Exception $e) {

            Logger::error('Alipay Query Exception: ', [
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
            'out_trade_no' => $trade->sn,
            'out_request_no' => $refund->sn,
            '_action' => "refund_{$trade->scene}",
        ]);
    }

    /**
     * 查询提现
     */
    public function queryWithdraw(WithdrawModel $withdraw): Collection|bool
    {
        return $this->query([
            'out_biz_no' => $withdraw->sn,
            'product_code' => 'TRANS_ACCOUNT_NO_PWD',
            'biz_scene' => 'DIRECT_TRANSFER',
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

        if ($response && isset($response['trade_status'])) {
            $result = $response['trade_status'] == 'TRADE_SUCCESS';
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

        if ($response && isset($response['refund_status'])) {
            $result = $response['refund_status'] == 'REFUND_SUCCESS';
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

        if ($response && isset($response['status'])) {
            $result = $response['status'] == 'SUCCESS';
        }

        return $result;
    }

    /**
     * 关闭交易
     */
    public function close(TradeModel $trade): bool
    {
        try {

            $order = [
                'out_trade_no' => $trade->sn,
                '_action' => $trade->scene,
            ];

            $response = $this->provider->close($order);

            $result = $response['code'] == '10000';

            Logger::info('Alipay Close Data: ', $response->all());

        } catch (\Exception $e) {

            Logger::error('Alipay Close Exception: ', [
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
     * 撤销交易
     */
    public function cancel(TradeModel $trade): bool
    {
        try {

            $order = [
                'out_trade_no' => $trade->sn,
                '_action' => $trade->scene,
            ];

            $response = $this->provider->cancel($order);

            $result = $response['code'] == '10000';

            Logger::info('Alipay Cancel Data: ', $response->all());

        } catch (\Exception $e) {

            Logger::error('Alipay Cancel Exception: ', [
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
     */
    public function refund(RefundModel $refund): bool
    {
        try {

            $tradeRepo = new TradeRepo();

            $trade = $tradeRepo->findById($refund->trade_id);

            $response = $this->provider->refund([
                'out_trade_no' => $trade->sn,
                'out_request_no' => $refund->sn,
                'refund_amount' => $refund->amount,
            ]);

            $result = $response['code'] == '10000';

            if (!$result) {
                $refund->error_note = kg_json_encode([
                    'code' => $response['sub_code'] ?: $response['code'],
                    'message' => $response['sub_msg'] ?: $response['msg'],
                ]);
                $refund->update();
            }

            Logger::info('Alipay Refund Data: ', $response->all());

        } catch (\Exception $e) {

            $refund->error_note = kg_json_encode([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $refund->update();

            Logger::error('Alipay Refund Exception: ', [
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

            $identityType = str_starts_with($account->identity, '2088') ? 'ALIPAY_USER_ID' : 'ALIPAY_OPEN_ID';

            /**
             * 转账场景（佣金报酬）说明
             * @link https://opendocs.alipay.com/open/0iaxid
             */
            $response = $this->provider->transfer([
                'out_biz_no' => $withdraw->sn,
                'trans_amount' => $withdraw->trans_amount,
                'product_code' => 'TRANS_ACCOUNT_NO_PWD',
                'biz_scene' => 'DIRECT_TRANSFER',
                'payee_info' => [
                    'identity' => $account->identity,
                    'identity_type' => $identityType,
                    'name' => $account->name,
                ],
                'remark' => '分销提现',
                'transfer_scene_name' => '佣金报酬', // 固定场景枚举名
                'transfer_scene_report_infos' => [
                    [
                        'info_type' => '佣金报酬说明',
                        'info_content' => sprintf('%s佣金提现', date('y年m月', $withdraw->create_time)),
                    ],
                ],
            ]);

            $result = $response['code'] == '10000';

            if (!$result) {
                $withdraw->error_note = kg_json_encode([
                    'code' => $response['sub_code'] ?: $response['code'],
                    'message' => $response['sub_msg'] ?: $response['msg'],
                ]);
                $withdraw->update();
            }

            Logger::info('Alipay Transfer Data: ', $response->all());

        } catch (\Exception $e) {

            $withdraw->error_note = kg_json_encode([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            $withdraw->update();

            Logger::error('Alipay Transfer Exception: ', [
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
     * 处理交易回调
     */
    protected function handleTradeCallback(Collection $data): ResponseInterface|false
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($data['out_trade_no']);

        if (!$trade) return false;

        if ($data['total_amount'] != $trade->amount) {
            return false;
        }

        if ($trade->status == TradeModel::STATUS_FINISHED) {
            return $this->provider->success();
        }

        if ($trade->status != TradeModel::STATUS_PENDING) {
            return false;
        }

        $trade->channel_sn = $data['trade_no'] ?: '';
        $trade->channel_identity = !empty($data['buyer_open_id']) ? $data['buyer_open_id'] : ($data['buyer_id'] ?? '');
        $trade->update();

        $this->eventsManager->fire('Trade:afterSuccess', $this, $trade);

        return $this->provider->success();
    }

    /**
     * 获取订单失效时间
     */
    protected function getTimeExpire(): string
    {
        $lifetime = kg_config('trade.lifetime', 15 * 60);

        return date('Y-m-d H:i:s', time() + $lifetime);
    }

}
