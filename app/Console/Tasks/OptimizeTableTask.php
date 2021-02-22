<?php

namespace App\Console\Tasks;

use App\Models\ImMessage as ImMessageModel;
use App\Models\Learning as LearningModel;
use App\Models\Task as TaskModel;

class OptimizeTableTask extends Task
{

    public function mainAction()
    {
        $this->optimizeImMessageTable();
        $this->optimizeLearningTable();
        $this->optimizeTaskTable();
    }

    protected function optimizeImMessageTable()
    {
        $count = ImMessageModel::count();

        if ($count < 1000000) return;

        $messageModel = new ImMessageModel();

        $tableName = $messageModel->getSource();

        $this->db->delete($tableName, "create_time < :create_time", [
            'create_time' => strtotime('-6 months'),
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");
    }

    protected function optimizeLearningTable()
    {
        $count = LearningModel::count();

        if ($count < 1000000) return;

        $learningModel = new LearningModel();

        $tableName = $learningModel->getSource();

        $this->db->delete($tableName, "create_time < :create_time", [
            'create_time' => strtotime('-6 months'),
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");
    }

    protected function optimizeTaskTable()
    {
        $count = TaskModel::count();

        if ($count < 1000000) return;

        $taskModel = new TaskModel();

        $tableName = $taskModel->getSource();

        $this->db->delete($tableName, "create_time < :create_time AND status > :status", [
            'create_time' => strtotime('-6 months'),
            'status' => TaskModel::STATUS_PENDING,
        ]);

        $this->db->execute("OPTIMIZE TABLE {$tableName}");
    }

}