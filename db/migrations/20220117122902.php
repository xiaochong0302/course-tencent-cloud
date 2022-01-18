<?php

use Phinx\Migration\AbstractMigration;

final class V20220117122902 extends AbstractMigration
{

    public function up()
    {
        $this->renameGiftRedeemTable();
        $this->handlePointHistory();
    }

    protected function renameGiftRedeemTable()
    {
        if ($this->table('kg_point_redeem')->exists()) {
            $this->table('kg_point_redeem')
                ->rename('kg_point_gift_redeem')
                ->save();
        }
    }

    protected function handlePointHistory()
    {
        $rows = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_point_history')
            ->whereInList('event_type', [2, 3])
            ->execute()->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) return;

        foreach ($rows as $row) {
            $eventInfo = json_decode($row['event_info'], true);
            $newEventInfo = json_encode([
                'point_gift_redeem' => $eventInfo['point_redeem']
            ]);
            $this->getQueryBuilder()
                ->update('kg_point_history')
                ->set('event_info', $newEventInfo)
                ->where(['id' => $row['id']])
                ->execute();
        }
    }

}
