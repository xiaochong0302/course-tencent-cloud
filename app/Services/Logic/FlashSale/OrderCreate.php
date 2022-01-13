<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\FlashSale;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\FlashSale as FlashSaleModel;
use App\Models\Order as OrderModel;
use App\Services\Logic\FlashSaleTrait;
use App\Services\Logic\Order\OrderCreate as OrderCreateService;
use App\Validators\FlashSale as FlashSaleValidator;
use App\Validators\Order as OrderValidator;

class OrderCreate extends OrderCreateService
{

    use FlashSaleTrait;

    public function handle()
    {
        $id = $this->request->getPost('id', 'int');

        $user = $this->getLoginUser();

        $sale = $this->checkFlashSale($id);

        $saleValidator = new FlashSaleValidator();

        $saleValidator->checkIfExpired($sale->end_time);
        $saleValidator->checkIfOutSchedules($sale->schedules);
        $saleValidator->checkIfNotPaid($user->id, $sale->id);

        $queue = new Queue();

        if ($queue->pop($id) === false) {
            throw new BadRequestException('flash_sale.out_stock');
        }

        $this->amount = $sale->price;
        $this->promotion_id = $sale->id;
        $this->promotion_type = OrderModel::PROMOTION_FLASH_SALE;
        $this->promotion_info = [
            'flash_sale' => [
                'id' => $sale->id,
                'price' => $sale->price,
            ]
        ];

        $orderValidator = new OrderValidator();

        $orderValidator->checkAmount($this->amount);

        try {

            $order = new OrderModel();

            if ($sale->item_type == FlashSaleModel::ITEM_COURSE) {

                $course = $orderValidator->checkCourse($sale->item_id);

                $orderValidator->checkIfBoughtCourse($user->id, $course->id);

                $order = $this->createCourseOrder($course, $user);

            } elseif ($sale->item_type == FlashSaleModel::ITEM_PACKAGE) {

                $package = $orderValidator->checkPackage($sale->item_id);

                $orderValidator->checkIfBoughtPackage($user->id, $package->id);

                $order = $this->createPackageOrder($package, $user);

            } elseif ($sale->item_type == FlashSaleModel::ITEM_VIP) {

                $vip = $orderValidator->checkVip($sale->item_id);

                $order = $this->createVipOrder($vip, $user);
            }

            $this->decrFlashSaleStock($sale);

            $this->saveUserOrderCache($user->id, $sale->id);

            return $order;

        } catch (\Exception $e) {

            $queue->push($sale->id);

            throw new BadRequestException($e->getMessage());
        }
    }

    protected function decrFlashSaleStock(FlashSaleModel $sale)
    {
        if ($sale->stock < 1) return;

        if ($sale->stock == 1) $sale->published = 0;

        $sale->stock -= 1;

        $sale->update();
    }

    protected function saveUserOrderCache($userId, $saleId)
    {
        $cache = new UserOrderCache();

        return $cache->save($userId, $saleId);
    }

}
