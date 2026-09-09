<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Setting extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 配置块
     *
     * @var string
     */
    public string $section = '';

    /**
     * 配置键
     *
     * @var string
     */
    public string $item_key = '';

    /**
     * 配置值
     *
     * @var string
     */
    public string $item_value = '';

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_setting');
    }

}
