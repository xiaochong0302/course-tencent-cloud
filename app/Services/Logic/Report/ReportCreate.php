<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Report;

use App\Models\Report as ReportModel;
use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Report as ReportValidator;
use App\Validators\UserLimit as UserLimitValidator;

class ReportCreate extends LogicService
{

    use ClientTrait;
    use CountTrait;

    public function handle()
    {
        $itemId = $this->request->getPost('item_id', ['trim', 'int']);
        $itemType = $this->request->getPost('item_type', ['trim', 'int']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);
        $remark = $this->request->getPost('remark', ['trim', 'string']);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyReportLimit($user);

        $validator = new ReportValidator();

        $item = $validator->checkItem($itemId, $itemType);

        $validator->checkIfReported($user->id, $itemId, $itemType);

        $report = new ReportModel();

        $report->reason = $validator->checkReason($reason, $remark);
        $report->client_type = $this->getClientType();
        $report->client_ip = $this->getClientIp();
        $report->item_type = $itemType;
        $report->item_id = $itemId;
        $report->owner_id = $user->id;

        $report->create();

        $this->incrUserDailyReportCount($user);

        $this->handleItemReportCount($item);

        $this->eventsManager->fire('Report:afterCreate', $this, $report);
    }

    protected function incrUserDailyReportCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrReportCount', $this, $user);
    }

}
