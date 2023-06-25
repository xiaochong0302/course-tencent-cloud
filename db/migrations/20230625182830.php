<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20230625182830 extends AbstractMigration
{

    public function up()
    {
        $this->deleteCaptchaSettings();
    }

    protected function deleteCaptchaSettings()
    {
        $this->getQueryBuilder()
            ->delete('kg_setting')
            ->where(['section' => 'captcha'])
            ->execute();
    }

}
