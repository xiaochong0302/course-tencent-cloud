<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'SettingTrait.php';

use Phinx\Migration\AbstractMigration;

final class V20210903040558 extends AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->handleVodSettings();
    }

    protected function handleVodSettings()
    {
        $row = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_setting')
            ->where(['section' => 'vod', 'item_key' => 'video_quality'])
            ->execute()->fetch(PDO::FETCH_ASSOC);

        /**
         * 数组索引不连续造成对象序列化后的数据不合乎要求
         */
        $itemValue = json_decode($row['item_value'], true);
        $itemValue = array_values($itemValue);
        $itemValue = json_encode($itemValue);

        $this->getQueryBuilder()
            ->update('kg_setting')
            ->set('item_value', $itemValue)
            ->where(['id' => $row['id']])
            ->execute();
    }

}
