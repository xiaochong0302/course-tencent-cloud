<?php

use Phinx\Migration\AbstractMigration;

final class V20210324064239 extends AbstractMigration
{

    public function up()
    {
        $orders = $this->findOrders();

        if ($orders->count() == 0) return;

        foreach ($orders as $order) {
            if ($order['item_type'] == 1) {
                $this->handleCourseOrder($order);
            } elseif ($order['item_type'] == 2) {
                $this->handlePackageOrder($order);
            }
        }
    }

    /**
     * 课程订单补充信息
     *
     * @param array $order
     */
    protected function handleCourseOrder($order)
    {
        $itemInfo = json_decode($order['item_info'], true);

        $course = $this->findCourseById($itemInfo['course']['id']);

        $itemInfo['course']['model'] = $course['model'];
        $itemInfo['course']['attrs'] = json_decode($course['attrs'], JSON_UNESCAPED_UNICODE);

        $this->updateOrderItemInfo($order['id'], $itemInfo);
    }

    /**
     * 套餐订单补充信息
     *
     * @param array $order
     */
    protected function handlePackageOrder($order)
    {
        $itemInfo = json_decode($order['item_info'], true);

        foreach ($itemInfo['courses'] as &$pkgCourse) {
            $course = $this->findCourseById($pkgCourse['id']);
            $pkgCourse['model'] = $course['model'];
            $pkgCourse['attrs'] = json_decode($course['attrs'], JSON_UNESCAPED_UNICODE);
        }

        $this->updateOrderItemInfo($order['id'], $itemInfo);
    }

    protected function updateOrderItemInfo($id, $itemInfo)
    {
        $itemInfo = json_encode($itemInfo, JSON_UNESCAPED_UNICODE);

        $this->getQueryBuilder()
            ->update('kg_order')
            ->set('item_info', $itemInfo)
            ->where(['id' => $id])
            ->execute();
    }

    protected function findCourseById($id)
    {
        return $this->getQueryBuilder()
            ->select('*')
            ->from('kg_course')
            ->where(['id' => $id])
            ->execute()->fetch('assoc');
    }

    protected function findOrders()
    {
        return $this->getQueryBuilder()
            ->select('*')
            ->from('kg_order')
            ->execute();
    }

}
