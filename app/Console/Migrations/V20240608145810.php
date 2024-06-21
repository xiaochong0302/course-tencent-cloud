<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use Phalcon\Mvc\Model\Resultset;

class V20240608145810 extends Migration
{

    public function run()
    {
        $this->handleReadChapters();
    }

    protected function handleReadChapters()
    {
        /**
         * @var $chapters Resultset|ChapterModel[]
         */
        $chapters = ChapterModel::query()
            ->where('model = :model:', ['model' => CourseModel::MODEL_READ])
            ->andWhere('parent_id > 0')
            ->execute();

        if ($chapters->count() == 0) return;

        foreach ($chapters as $chapter) {

            $attrs = $chapter->attrs;

            if (isset($attrs['format'])) continue;

            $attrs['format'] = 'html';

            $chapter->attrs = $attrs;

            $chapter->update();
        }
    }

}