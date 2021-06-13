<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\FlashSale as FlashSaleCache;
use App\Caches\MaxFlashSaleId as MaxFlashSaleIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\FlashSale as FlashSaleModel;
use App\Repos\FlashSale as FlashSaleRepo;
use App\Services\Logic\FlashSale\UserOrderCache;

class FlashSale extends Validator
{

    /**
     * @param int $id
     * @return FlashSaleModel
     * @throws BadRequestException
     */
    public function checkFlashSaleCache($id)
    {
        $this->checkId($id);

        $saleCache = new FlashSaleCache();

        $sale = $saleCache->get($id);

        if (!$sale) {
            throw new BadRequestException('flash_sale.not_found');
        }

        return $sale;
    }

    public function checkFlashSale($id)
    {
        $saleRepo = new FlashSaleRepo();

        $sale = $saleRepo->findById($id);

        if (!$sale) {
            throw new BadRequestException('flash_sale.not_found');
        }

        return $sale;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxSaleIdCache = new MaxFlashSaleIdCache();

        $maxId = $maxSaleIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('flash_sale.not_found');
        }
    }

    public function checkItemType($type)
    {
        $list = FlashSaleModel::itemTypes();

        if (!array_key_exists($type, $list)) {
            throw new BadRequestException('flash_sale.invalid_item_type');
        }

        return (int)$type;
    }

    public function checkStartTime($startTime)
    {
        if (!CommonValidator::date($startTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('flash_sale.invalid_start_time');
        }

        return strtotime($startTime);
    }

    public function checkEndTime($endTime)
    {
        if (!CommonValidator::date($endTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('flash_sale.invalid_end_time');
        }

        return strtotime($endTime);
    }

    public function checkTimeRange($startTime, $endTime)
    {
        if ($startTime >= $endTime) {
            throw new BadRequestException('flash_sale.start_gt_end');
        }
    }

    public function checkSchedules($schedules)
    {
        if (empty($schedules)) {
            throw new BadRequestException('flash_sale.invalid_schedules');
        }

        $result = explode(',', $schedules);

        sort($result);

        return $result;
    }

    public function checkStock($stock)
    {
        $value = $this->filter->sanitize($stock, ['trim', 'int']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('flash_sale.invalid_stock');
        }

        return (int)$value;
    }

    public function checkPrice($marketPrice, $salePrice)
    {
        if ($salePrice < 0.01) {
            throw new BadRequestException('flash_sale.invalid_price');
        }

        if ($salePrice > $marketPrice) {
            throw new BadRequestException('flash_sale.unreasonable_price');
        }

        return (float)$salePrice;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('flash_sale.invalid_publish_status');
        }

        return (int)$status;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkPackage($id)
    {
        $validator = new Package();

        return $validator->checkPackage($id);
    }

    public function checkVip($id)
    {
        $validator = new Vip();

        return $validator->checkVip($id);
    }

    public function checkIfExpired($endTime)
    {
        if ($endTime < time()) {
            throw new BadRequestException('flash_sale.expired');
        }
    }

    public function checkIfOutSchedules($schedules)
    {
        $curHour = date('H');

        $flag = true;

        foreach ($schedules as $schedule) {
            if ($curHour >= $schedule && $curHour < $schedule + 2) {
                $flag = false;
            }
        }

        if ($flag) {
            throw new BadRequestException('flash_sale.out_schedules');
        }
    }

    public function checkIfNotPaid($userId, $saleId)
    {
        $cache = new UserOrderCache();

        if ($cache->get($userId, $saleId)) {
            throw new BadRequestException('flash_sale.not_paid');
        }
    }

}
