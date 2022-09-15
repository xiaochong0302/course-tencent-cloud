<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Topic;

use App\Models\Topic as TopicModel;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\TopicTrait;

class TopicInfo extends LogicService
{

    use TopicTrait;

    public function handle($id)
    {
        $topic = $this->checkTopic($id);

        return $this->handleTopic($topic);
    }

    protected function handleTopic(TopicModel $topic)
    {
        return [
            'id' => $topic->id,
            'title' => $topic->title,
            'topic' => $topic->cover,
            'summary' => $topic->summary,
            'published' => $topic->published,
            'deleted' => $topic->deleted,
            'create_time' => $topic->create_time,
            'update_time' => $topic->update_time,
        ];
    }

}
