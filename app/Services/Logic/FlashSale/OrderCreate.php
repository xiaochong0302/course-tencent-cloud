<?php

namespace App\Services\Logic\FlashSale;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\FlashSale as FlashSaleModel;
use App\Models\Order as OrderModel;
use App\Services\Logic\FlashSaleTrait;
use App\Validators\FlashSale as FlashSaleValidator;
use App\Validators\Order as OrderValidator;

class OrderCreate extends \App\Services\Logic\Order\OrderCreate
{

    use FlashSaleTrait;

    public function handle()
    {
        $id = $this->request->getPost('id', 'int');

        $user = $this->getLoginUser();

        $sale = $this->checkFlashSale($id);

        $validator = new FlashSaleValidator;

        $validator->checkIfExpired($sale->end_time);
        $validator->checkIfOutSchedules($sale->schedules);
        $validator->checkIfNotPaid($user->id, $sale->id);

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

        try {

            $order = new OrderModel();

            $validator = new OrderValidator();

            if ($sale->item_type == FlashSaleModel::ITEM_COURSE) {

                $course = $validator->checkCourse($sale->item_id);

                $validator->checkIfBoughtCourse($user->id, $course->id);

                $this->handleCoursePromotion();

                $order = $this->createCourseOrder($course, $user);

            } elseif ($sale->item_type == FlashSaleModel::ITEM_PACKAGE) {

                $package = $validator->checkPackage($sale->item_id);

                $validator->checkIfBoughtPackage($user->id, $package->id);

                $order = $this->createPackageOrder($package, $user);

            } elseif ($sale->item_type == FlashSaleModel::ITEM_VIP) {

                $vip = $validator->checkVip($sale->item_id);

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

    protected function handleCoursePromotion()
    {
    }

    protected function handlePackagePromotion()
    {
    }

    protected function handleVipPromotion()
    {
    }

    protected function decrFlashSaleStock(FlashSaleModel $sale)
    {
        if ($sale->stock > 1) {
            $sale->stock -= 1;
            $sale->update();
        }
    }

    protected function saveUserOrderCache($userId, $saleId)
    {
        $cache = new UserOrderCache();

        return $cache->save($userId, $saleId);
    }

}
