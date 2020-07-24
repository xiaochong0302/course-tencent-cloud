<?php

namespace App\Models;

class Area extends Model
{

    /**
     * 区域类型
     */
    const TYPE_PROVINCE = 'province';
    const TYPE_CITY = 'city';
    const TYPE_COUNTY = 'county';

    /**
     * 主键
     *
     * @var int
     */
    public $id;

    /**
     * 类型
     *
     * @var string
     */
    public $type;

    /**
     * 编码
     *
     * @var string
     */
    public $code;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    public function getSource(): string
    {
        return 'kg_area';
    }

}
