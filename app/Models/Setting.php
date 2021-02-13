<?php

namespace App\Models;

class Setting extends Model
{

    /**
     * 主键
     *
     * @var int
     */
    public $id = 0;

    /**
     * 配置块
     *
     * @var string
     */
    public $section = '';

    /**
     * 配置键
     *
     * @var string
     */
    public $item_key = '';

    /**
     * 配置值
     *
     * @var string
     */
    public $item_value = '';

    public function getSource(): string
    {
        return 'kg_setting';
    }

}