<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Topic as TopicModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseTopicList extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_topic_list:{$id}";
    }

    public function getContent($id = null)
    {
        $topics = $this->findTopics(5);

        if ($topics->count() == 0) {
            return [];
        }

        return $this->handleContent($topics);
    }

    /**
     * @param TopicModel[] $topics
     * @return array
     */
    public function handleContent($topics)
    {
        $result = [];

        foreach ($topics as $topic) {

            $result[] = [
                'id' => $topic->id,
                'title' => $topic->title,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TopicModel[]
     */
    public function findTopics($limit = 5)
    {
        return TopicModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
