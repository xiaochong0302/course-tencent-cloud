<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class MigrationTask extends Model
{

    /**
     * 主键
     *
     * @var int
     */
    public $id = 0;

    /**
     * 版本
     *
     * @var string
     */
    public $version = '';

    /**
     * 开始时间
     *
     * @var int
     */
    public $start_time = 0;

    /**
     * 结束时间
     *
     * @var int
     */
    public $end_time = 0;

    public function getSource(): string
    {
        return 'kg_migration_task';
    }

}
