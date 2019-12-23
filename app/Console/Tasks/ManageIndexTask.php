<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Searchers\Course as CourseSearcher;
use Phalcon\Cli\Task;

class ManageIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php index search {type} {query}
     * @param array $params
     * @throws \XSException
     */
    public function searchAction($params)
    {
        $type = $params[0] ?? null;
        $query = $params[1] ?? null;

        if (!in_array($type, $this->getItemTypes())) {
            exit("Invalid item type" . PHP_EOL);
        }

        if (!$query) {
            exit("Please special a query word" . PHP_EOL);
        }

        $result = [];

        if ($type == 'course') {
            $result = $this->searchCourses($query);
        }

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php index clean {type}
     * @param array $params
     */
    public function cleanAction($params)
    {
        $type = $params[0] ?? null;

        if (in_array($type, $this->getItemTypes())) {
            exit("Invalid item type" . PHP_EOL);
        }

        if ($type == 'course') {
            $this->cleanCourseIndex();
        }
    }

    /**
     * 重建索引
     *
     * @command: php console.php index rebuild {type}
     * @param array $params
     */
    public function rebuildAction($params)
    {
        $type = $params[0] ?? null;

        if (in_array($type, $this->getItemTypes())) {
            exit("Invalid item type" . PHP_EOL);
        }

        if ($type == 'course') {
            $this->rebuildCourseIndex();
        }
    }

    /**
     * 清空课程索引
     */
    protected function cleanCourseIndex()
    {
        $searcher = new CourseSearcher();

        $index = $searcher->getXS()->getIndex();

        echo "Start clean index" . PHP_EOL;

        $index->clean();

        echo "End clean index" . PHP_EOL;
    }

    /**
     * 重建课程索引
     */
    protected function rebuildCourseIndex()
    {
        $courses = $this->findCourses();

        if ($courses->count() == 0) {
            return;
        }

        $searcher = new CourseSearcher();

        $index = $searcher->getXS()->getIndex();

        echo "Start rebuild index" . PHP_EOL;

        $index->beginRebuild();

        foreach ($courses as $course) {
            $document = $searcher->setDocument($course);
            $index->add($document);
        }

        $index->endRebuild();

        echo "End rebuild index" . PHP_EOL;
    }

    /**
     * 搜索课程
     *
     * @param string $query
     * @return \stdClass $result
     * @throws \XSException
     */
    protected function searchCourses($query)
    {
        $searcher = new CourseSearcher();

        $result = $searcher->search($query);

        return $result;
    }

    /**
     * 查找课程
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    protected function findCourses()
    {
        $courses = CourseModel::query()
            ->where('published = 1')
            ->execute();

        return $courses;
    }

    /**
     * 获取条目类型
     *
     * @return array
     */
    protected function getItemTypes()
    {
        $types = ['course'];

        return $types;
    }

}
