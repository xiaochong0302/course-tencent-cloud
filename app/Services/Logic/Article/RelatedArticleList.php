<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Caches\TaggedArticleList as TaggedArticleListCache;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;

class RelatedArticleList extends LogicService
{

    use ArticleTrait;

    public function handle($id)
    {
        $limit = $this->request->getQuery('limit', 'int', 5);

        $article = $this->checkArticle($id);

        if (empty($article->tags)) return [];

        $tagIds = kg_array_column($article->tags, 'id');

        $tagId = kg_array_rand($tagIds);

        $cache = new TaggedArticleListCache();

        $articles = $cache->get($tagId);

        if (empty($articles)) return [];

        foreach ($articles as $key => $article) {
            if ($article['id'] == $id) {
                unset($articles[$key]);
            }
        }

        if ($limit < count($articles)) {
            $articles = array_slice($articles, $limit);
        }

        return $articles;
    }

}
