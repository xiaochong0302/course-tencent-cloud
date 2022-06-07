<?php

use Phinx\Migration\AbstractMigration;

final class V20220607014823 extends AbstractMigration
{

    public function up()
    {
        $this->handleSiteSettings();
    }

    protected function handleSiteSettings()
    {
        $row =
            [
                [
                    'section' => 'site',
                    'item_key' => 'isp_sn',
                    'item_value' => '',
                ],
                [
                    'section' => 'site',
                    'item_key' => 'isp_link',
                    'item_value' => 'https://dxzhgl.miit.gov.cn',
                ],
                [
                    'section' => 'site',
                    'item_key' => 'company_sn',
                    'item_value' => '',
                ],
                [
                    'section' => 'site',
                    'item_key' => 'company_sn_link',
                    'item_value' => '',
                ],
            ];

        $this->table('kg_setting')->insert($row)->save();
    }

}