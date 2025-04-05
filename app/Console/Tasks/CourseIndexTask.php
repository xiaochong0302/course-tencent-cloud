<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Services\Search\CourseDocument;
use App\Services\Search\CourseSearcher;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php course_index search {query}
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit('please special a query word' . PHP_EOL);
        }

        $handler = new CourseSearcher();

        $result = $handler->search($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php course_index clean
     */
    public function cleanAction()
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean course index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean course index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     *
     * @command: php console.php course_index rebuild
     */
    public function rebuildAction()
    {
        $courses = $this->findCourses();

        if ($courses->count() == 0) return;

        $handler = new CourseSearcher();

        $doc = new CourseDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild course index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($courses as $course) {
            $document = $doc->setDocument($course);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild course index ------' . PHP_EOL;
    }

    /**
     * 刷新索引缓存
     *
     * @command: php console.php course_index flush_index
     */
    public function flushIndexAction()
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush course index ------' . PHP_EOL;

        $index->flushIndex();

        echo '------ end flush course index ------' . PHP_EOL;
    }

    /**
     * 刷新搜索日志
     *
     * @command: php console.php course_index flush_logging
     */
    public function flushLoggingAction()
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush course logging ------' . PHP_EOL;

        $index->flushLogging();

        echo '------ end flush course logging ------' . PHP_EOL;
    }

    /**
     * 查找课程
     *
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findCourses()
    {
        return CourseModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->execute();
    }

}
