<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Models\Tag as TagModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\Tag as TagRepo;
use App\Services\Logic\Service as LogicService;

class XmTagList extends LogicService
{

    public function handle($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($allTags->count() == 0) return [];

        $articleTagIds = [];

        if ($id > 0) {
            $article = $this->findArticle($id);
            if (!empty($article->tags)) {
                $articleTagIds = kg_array_column($article->tags, 'id');
            }
        }

        $list = [];

        foreach ($allTags as $tag) {
            $case1 = is_string($tag->scopes) && $tag->scopes == 'all';
            $case2 = is_array($tag->scopes) && in_array(TagModel::SCOPE_ARTICLE, $tag->scopes);
            if ($case1 || $case2) {
                $selected = in_array($tag->id, $articleTagIds);
                $list[] = [
                    'name' => $tag->name,
                    'value' => $tag->id,
                    'selected' => $selected,
                ];
            }
        }

        return $list;
    }

    protected function findArticle($id)
    {
        $articleRepo = new ArticleRepo();

        return $articleRepo->findById($id);
    }

}
