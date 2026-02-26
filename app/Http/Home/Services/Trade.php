<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Services\Logic\Trade\QrCode as TradeQrCodeService;
use App\Services\Logic\Trade\TradeCreate as TradeCreateService;

class Trade extends Service
{

    public function create()
    {
        try {

            $this->db->begin();

            $service = new TradeCreateService();

            $trade = $service->handle();

            $service = new TradeQrCodeService();

            $qrCode = $service->handle($trade);

            $this->db->commit();

            return [
                'sn' => $trade->sn,
                'channel' => $trade->channel,
                'qrcode' => $qrCode,
            ];

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('trade');

            $logger->error('Create Trade Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
