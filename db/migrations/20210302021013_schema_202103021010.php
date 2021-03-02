<?php

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202103021010 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $this->deleteCourseTeachers();

        if ($this->table('kg_chapter_like')->hasColumn('deleted')) {
            $this->table('kg_chapter_like')->removeColumn('deleted');
        }

        if ($this->table('kg_chapter_user')->hasColumn('deleted')) {
            $this->table('kg_chapter_user')->removeColumn('deleted');
        }

        if ($this->table('kg_consult_like')->hasColumn('deleted')) {
            $this->table('kg_consult_like')->removeColumn('deleted');
        }

        if ($this->table('kg_course_favorite')->hasColumn('deleted')) {
            $this->table('kg_course_favorite')->removeColumn('deleted');
        }

        if ($this->table('kg_learning')->hasColumn('deleted')) {
            $this->table('kg_learning')->removeColumn('deleted');
        }

        if ($this->table('kg_point_history')->hasColumn('deleted')) {
            $this->table('kg_point_history')->removeColumn('deleted');
        }

        if ($this->table('kg_review_like')->hasColumn('deleted')) {
            $this->table('kg_review_like')->removeColumn('deleted');
        }

        if ($this->table('kg_user_balance')->hasColumn('deleted')) {
            $this->table('kg_user_balance')->removeColumn('deleted');
        }

        if ($this->table('kg_user_contact')->hasColumn('deleted')) {
            $this->table('kg_user_contact')->removeColumn('deleted');
        }

        if ($this->table('kg_user_session')->hasColumn('deleted')) {
            $this->table('kg_user_session')->removeColumn('deleted');
        }

        if ($this->table('kg_user_token')->hasColumn('deleted')) {
            $this->table('kg_user_token')->removeColumn('deleted');
        }

        if ($this->table('kg_wechat_subscribe')->hasColumn('deleted')) {
            $this->table('kg_wechat_subscribe')->removeColumn('deleted');
        }

        if ($this->table('kg_user_session')->hasColumn('expire_time') == false) {
            $this->table('kg_user_session')->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'client_ip',
            ])->save();
        }

        if ($this->table('kg_user_token')->hasColumn('expire_time') == false) {
            $this->table('kg_user_token')->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'client_ip',
            ])->save();
        }
    }

    protected function deleteCourseTeachers()
    {
        $this->getQueryBuilder()
            ->delete('kg_course_user')
            ->where(['role_type' => 2, 'deleted' => 1])
            ->execute();
    }

}
