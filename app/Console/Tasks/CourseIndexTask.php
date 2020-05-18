<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Services\Search\CourseDocument;
use App\Services\Search\CourseSearcher;
use Phalcon\Cli\Task;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php course_index search {query}
     * @param array $params
     * @throws \XSException
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit("please special a query word" . PHP_EOL);
        }

        $result = $this->searchCourses($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php course_index clean
     */
    public function cleanAction()
    {
        $this->cleanCourseIndex();
    }

    /**
     * 重建索引
     *
     * @command: php console.php course_index rebuild
     */
    public function rebuildAction()
    {
        $this->rebuildCourseIndex();
    }

    /**
     * 清空索引
     */
    protected function cleanCourseIndex()
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo "start clean index" . PHP_EOL;

        $index->clean();

        echo "end clean index" . PHP_EOL;
    }

    /**
     * 重建索引
     */
    protected function rebuildCourseIndex()
    {
        $courses = $this->findCourses();

        if ($courses->count() == 0) {
            return;
        }

        $handler = new CourseSearcher();

        $documenter = new CourseDocument();

        $index = $handler->getXS()->getIndex();

        echo "start rebuild index" . PHP_EOL;

        $index->beginRebuild();

        foreach ($courses as $course) {
            $document = $documenter->setDocument($course);
            $index->add($document);
        }

        $index->endRebuild();

        echo "end rebuild index" . PHP_EOL;
    }

    /**
     * 搜索课程
     *
     * @param string $query
     * @return array
     * @throws \XSException
     */
    protected function searchCourses($query)
    {
        $handler = new CourseSearcher();

        return $handler->search($query);
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
            ->execute();
    }

}
