<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'SettingTrait.php';

use Phinx\Migration\AbstractMigration;

final class V20230611193031 extends AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->handleDingTalkRobotSettings();
    }

    protected function handleDingTalkRobotSettings()
    {
        $row = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => 'dingtalk.robot'])
            ->andWhere(['item_key' => 'app_token'])
            ->execute()->fetch(PDO::FETCH_ASSOC);

        $webhookUrl = '';

        /**
         * 直接使用webhook地址，不用单独分离出access_token，简化用户操作
         */
        if (!empty($row['item_value'])) {
            $webhookUrl = "https://oapi.dingtalk.com/robot/send?access_token={$row['item_value']}";
        }

        $rows = [
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'webhook_url',
                'item_value' => $webhookUrl,
            ],
        ];

        $this->insertSettings($rows);
    }

}
