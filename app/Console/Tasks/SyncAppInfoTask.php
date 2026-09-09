<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Http\Admin\Services\Koogua as KooguaService;
use App\Library\Utils\Lock as LockUtil;

class SyncAppInfoTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        echo '------ start sync app info ------' . PHP_EOL;

        $kooguaService = new KooguaService();

        $kooguaService->syncLicenseCache();

        $kooguaService->syncAppInfo();

        echo '------ end of sync app info ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

}
