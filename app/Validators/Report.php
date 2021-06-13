<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Reason as ReasonModel;
use App\Models\Report as ReportModel;
use App\Repos\Report as ReportRepo;

class Report extends Validator
{

    public function checkReport($id)
    {
        $reportRepo = new ReportRepo();

        $report = $reportRepo->findById($id);

        if (!$report) {
            throw new BadRequestException('report.not_found');
        }

        return $report;
    }

    public function checkItem($itemId, $itemType)
    {
        if (!array_key_exists($itemType, ReportModel::itemTypes())) {
            throw new BadRequestException('comment.invalid_item_type');
        }

        $result = null;

        switch ($itemType) {
            case ReportModel::ITEM_ARTICLE:
                $validator = new Article();
                $result = $validator->checkArticle($itemId);
                break;
            case ReportModel::ITEM_QUESTION:
                $validator = new Question();
                $result = $validator->checkQuestion($itemId);
                break;
            case ReportModel::ITEM_ANSWER:
                $validator = new Answer();
                $result = $validator->checkAnswer($itemId);
                break;
            case ReportModel::ITEM_COMMENT:
                $validator = new Comment();
                $result = $validator->checkComment($itemId);
                break;
        }

        return $result;
    }

    public function checkReason($reason, $remark)
    {
        $reason = $this->filter->sanitize($reason, ['trim', 'int']);
        $remark = $this->filter->sanitize($remark, ['trim', 'string']);

        $options = ReasonModel::reportOptions();

        if (!array_key_exists($reason, $options)) {
            throw new BadRequestException('report.reason_required');
        }

        $value = $options[$reason];

        if ($reason == '105') {
            if (empty($remark)) {
                throw new BadRequestException('report.remark_required');
            }
            $value = $remark;
        }

        return $value;
    }

    public function checkIfReported($userId, $itemId, $itemType)
    {
        $reportRepo = new ReportRepo();

        $report = $reportRepo->findUserReport($userId, $itemId, $itemType);

        if ($report) {
            throw new BadRequestException('report.has_reported');
        }
    }

}
