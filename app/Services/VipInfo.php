<?php

namespace App\Services;

class VipInfo extends Service
{

    protected $config;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('vip');
    }

    /**
     * 获取条目
     *
     * @param string $duration
     * @return array
     */
    public function getItem($duration)
    {
        $items = $this->getItems();

        foreach ($items as $item) {
            if ($item['duration'] == $duration) {
                return $item;
            }
        }

        return $items[0];
    }

    /**
     * 获取条目列表
     *
     * @return array
     */
    public function getItems()
    {
        $items = [
            [
                'duration' => 'one_month',
                'label' => '1个月',
                'price' => $this->config['one_month'],
            ],
            [
                'duration' => 'three_month',
                'label' => '3个月',
                'price' => $this->config['three_month'],
            ],
            [
                'duration' => 'six_month',
                'label' => '6个月',
                'price' => $this->config['six_month'],
            ],
            [
                'duration' => 'twelve_month',
                'label' => '12个月',
                'price' => $this->config['twelve_month'],
            ],
        ];

        return $items;
    }

}
