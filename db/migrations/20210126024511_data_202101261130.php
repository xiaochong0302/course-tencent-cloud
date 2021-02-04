<?php

class Data202101261130 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $consumeRule = ['enabled' => 1, 'rate' => 5];

        $eventRule = [
            'account_register' => ['enabled' => 1, 'point' => 100],
            'site_visit' => ['enabled' => 1, 'point' => 10],
            'course_review' => ['enabled' => 1, 'point' => 50],
            'group_discuss' => ['enabled' => 1, 'point' => 10],
            'lesson_learning' => ['enabled' => 1, 'point' => 10],
        ];

        $rows = [
            [
                'section' => 'point',
                'item_key' => 'enabled',
                'item_value' => 1,
            ],
            [
                'section' => 'point',
                'item_key' => 'consume_rule',
                'item_value' => json_encode($consumeRule),
            ],
            [
                'section' => 'point',
                'item_key' => 'event_rule',
                'item_value' => json_encode($eventRule),
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'point'])
            ->execute();
    }

    protected function initUserBalanceData()
    {
        $dataQuery = $this->getQueryBuilder()->select(['id'])->from('kg_user');

        $this->getQueryBuilder()
            ->insert(['user_id'])
            ->into('kg_user_balance')
            ->values($dataQuery)
            ->execute();
    }

}
