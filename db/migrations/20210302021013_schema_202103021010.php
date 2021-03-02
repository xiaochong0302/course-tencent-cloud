<?php

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202103021010 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $this->deleteCourseTeachers();

        $table = $this->table('kg_chapter_like');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_chapter_user');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_consult_like');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_course_favorite');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_learning');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_point_history');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_review_like');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_user_balance');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_user_contact');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_user_session');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_user_token');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_wechat_subscribe');

        if ($table->hasColumn('deleted')) {
            $table->removeColumn('deleted')->save();
        }

        $table = $this->table('kg_user_session');

        if ($table->hasColumn('expire_time') == false) {
            $table->addColumn('expire_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '过期时间',
                'after' => 'client_ip',
            ])->save();
        }

        $table = $this->table('kg_user_token');

        if ($table->hasColumn('expire_time') == false) {
            $table->addColumn('expire_time', 'integer', [
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
