<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class MigrationPhalcon extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 版本
     *
     * @var string
     */
    public string $version = '';

    /**
     * 开始时间
     *
     * @var int
     */
    public int $start_time = 0;

    /**
     * 结束时间
     *
     * @var int
     */
    public int $end_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_migration_phalcon');
    }

}
