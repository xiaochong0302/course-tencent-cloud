<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Tag as TagModel;
use App\Repos\Tag as TagRepo;

class SyncTagStatTask extends Task
{

    public function mainAction()
    {
        $tags = $this->findTags();

        echo sprintf('pending tags: %s', $tags->count()) . PHP_EOL;

        if ($tags->count() == 0) return;

        echo '------ start sync tag stat task ------' . PHP_EOL;

        foreach ($tags as $tag) {
            $this->recountTaggedItems($tag);
        }

        echo '------ end sync tag stat task ------' . PHP_EOL;
    }

    protected function recountTaggedItems(TagModel $tag)
    {
        $tagRepo = new TagRepo();

        $tag->follow_count = $tagRepo->countFollows($tag->id);
        $tag->course_count = $tagRepo->countCourses($tag->id);
        $tag->article_count = $tagRepo->countArticles($tag->id);
        $tag->question_count = $tagRepo->countQuestions($tag->id);

        $tag->update();
    }

    protected function findTags()
    {
        return TagModel::query()
            ->where('published = 1')
            ->execute();
    }

}
