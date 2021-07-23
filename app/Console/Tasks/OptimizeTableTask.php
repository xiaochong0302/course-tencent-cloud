<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\ImMessage as ImMessageModel;
use App\Models\Learning as LearningModel;
use App\Models\Task as TaskModel;
use App\Models\UserSession as UserSessionModel;
use App\Models\UserToken as UserTokenModel;

class OptimizeTableTask extends Task
{

    public function mainAction()
    {
        $this->optimizeUserSessionTable();
        $this->optimizeUserTokenTable();
        $this->optimizeImMessageTable();
        $this->optimizeLearningTable();
        $this->optimizeTaskTable();
    }

    protected function optimizeUserSessionTable()
    {
        $sessionModel = new UserSessionModel();

        $tableName = $sessionModel->getSource();

        if (UserSessionModel::count() < 1000000) {
            echo sprintf('no need to optimize table: %s', $tableName) . PHP_EOL;
            return;
        }

        echo sprintf('------ start optimize table: %s ------', $tableName) . PHP_EOL;

        $this->db->delete($tableName, 'expire_time < :expire_time', [
            'expire_time' => strtotime('-3 days'),
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");

        echo sprintf('------ end optimize table: %s ------', $tableName) . PHP_EOL;
    }

    protected function optimizeUserTokenTable()
    {
        $tokenModel = new UserTokenModel();

        $tableName = $tokenModel->getSource();

        if (UserTokenModel::count() < 1000000) {
            echo sprintf('no need to optimize table: %s', $tableName) . PHP_EOL;
            return;
        }

        echo sprintf('------ start optimize table: %s ------', $tableName) . PHP_EOL;

        $this->db->delete($tableName, 'expire_time < :expire_time', [
            'expire_time' => strtotime('-3 days'),
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");

        echo sprintf('------ end optimize table: %s ------', $tableName) . PHP_EOL;
    }

    protected function optimizeImMessageTable()
    {
        $messageModel = new ImMessageModel();

        $tableName = $messageModel->getSource();

        if (ImMessageModel::count() < 1000000) {
            echo sprintf('no need to optimize table: %s', $tableName) . PHP_EOL;
            return;
        }

        echo sprintf('------ start optimize table: %s ------', $tableName) . PHP_EOL;

        $this->db->delete($tableName, 'create_time < :create_time', [
            'create_time' => strtotime('-6 months'),
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");

        echo sprintf('------ end optimize table: %s ------', $tableName) . PHP_EOL;
    }

    protected function optimizeLearningTable()
    {
        $learningModel = new LearningModel();

        $tableName = $learningModel->getSource();

        if (LearningModel::count() < 1000000) {
            echo sprintf('no need to optimize table: %s', $tableName) . PHP_EOL;
            return;
        }

        echo sprintf('------ start optimize table: %s ------', $tableName) . PHP_EOL;

        $this->db->delete($tableName, 'create_time < :create_time', [
            'create_time' => strtotime('-6 months'),
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");

        echo sprintf('------ end optimize table: %s ------', $tableName) . PHP_EOL;
    }

    protected function optimizeTaskTable()
    {
        $taskModel = new TaskModel();

        $tableName = $taskModel->getSource();

        if (TaskModel::count() < 1000000) {
            echo sprintf('no need to optimize table: %s', $tableName) . PHP_EOL;
            return;
        }

        echo sprintf('------ start optimize table: %s ------', $tableName) . PHP_EOL;

        $this->db->delete($tableName, 'create_time < :create_time AND status > :status', [
            'create_time' => strtotime('-6 months'),
            'status' => TaskModel::STATUS_PENDING,
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");

        echo sprintf('------ end optimize table: %s ------', $tableName) . PHP_EOL;
    }

}