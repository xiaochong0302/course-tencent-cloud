<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'SettingTrait.php';

use Phinx\Migration\AbstractMigration;

final class V20220607014823 extends AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->handleSiteSettings();
    }

    protected function handleSiteSettings()
    {
        $rows = [
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

        $this->insertSettings($rows);
    }

}