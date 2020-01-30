<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Models\Topic as TopicModel;
use Phalcon\Cli\Task;

class TopicSpiderTask extends Task
{

    public function mainAction()
    {
        $courses = CourseModel::query()
            ->columns(['id'])
            ->where('id > 810')
            ->orderBy('id ASC')
            ->execute();

        foreach ($courses as $course) {
            $this->handleList($course->id);
            sleep(5);
        }
    }

    protected function handleList($courseId)
    {
        $url = "https://www.imooc.com/course/ajaxskillcourse?cid={$courseId}";

        $content = file_get_contents($url);

        $result = json_decode($content, true);

        $topics = $result['data'];

        echo "============== Course {$courseId} =================" . PHP_EOL;

        if (empty($topics)) {
            return;
        }

        foreach ($topics as $item) {

            $topicData = [
                'id' => $item['subject_id'],
                'title' => $item['title'],
                'alias' => $this->getAlias($item['url']),
            ];

            $topic = TopicModel::findFirst($topicData['id']);

            if (!$topic) {
                $topic = new TopicModel();
                $topic->create($topicData);
            }
        }

    }

    protected function getAlias($url)
    {
        $result = str_replace('//www.imooc.com/topic/', '', $url);
        return trim($result);
    }


}
