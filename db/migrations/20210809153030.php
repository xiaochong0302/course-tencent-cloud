<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once 'SettingTrait.php';

use Phinx\Migration\AbstractMigration;

final class V20210809153030 extends AbstractMigration
{

    use SettingTrait;

    public function up()
    {
        $this->handleVodSettings();
        $this->handleRemotePlayUrl();
    }

    protected function handleVodSettings()
    {
        $rows = [
            [
                'section' => 'vod',
                'item_key' => 'video_quality',
                'item_value' => json_encode(['hd', 'sd', 'fd']),
            ],
            [
                'section' => 'vod',
                'item_key' => 'audio_quality',
                'item_value' => json_encode(['sd']),
            ],
        ];

        $this->insertSettings($rows);
    }

    protected function handleRemotePlayUrl()
    {
        $rows = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_chapter_vod')
            ->where(['file_remote !=' => '[]'])
            ->execute();

        if ($rows->count() == 0) return;

        foreach ($rows as $row) {

            $value = json_decode($row['file_remote'], true);

            if (isset($value['od']['url'])) {

                $newValue = json_encode([
                    'hd' => ['url' => $value['od']['url']],
                    'sd' => ['url' => $value['hd']['url']],
                    'fd' => ['url' => $value['sd']['url']],
                ]);

                $this->updateFileRemote($row['id'], $newValue);
            }
        }
    }

    protected function updateFileRemote($id, $fileRemote)
    {
        $this->getQueryBuilder()
            ->update('kg_chapter_vod')
            ->where(['id' => $id])
            ->set('file_remote', $fileRemote)
            ->execute();
    }

}
