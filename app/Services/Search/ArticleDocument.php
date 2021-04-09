<?php

namespace App\Services\Search;

use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\User\Component;

class ArticleDocument extends Component
{

    /**
     * 设置文档
     *
     * @param ArticleModel $article
     * @return \XSDocument
     */
    public function setDocument(ArticleModel $article)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($article);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param ArticleModel $article
     * @return array
     */
    public function formatDocument(ArticleModel $article)
    {
        if (is_array($article->tags) || is_object($article->tags)) {
            $article->tags = kg_json_encode($article->tags);
        }

        $owner = '';

        if ($article->owner_id > 0) {
            $record = UserModel::findFirst($article->owner_id);
            $owner = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $category = '';

        if ($article->category_id > 0) {
            $record = CategoryModel::findFirst($article->category_id);
            $category = kg_json_encode([
                'id' => $record->id,
                'name' => $record->name,
            ]);
        }

        $article->cover = ArticleModel::getCoverPath($article->cover);

        if (empty($article->summary)) {
            $article->summary = kg_parse_summary($article->content);
        }

        return [
            'id' => $article->id,
            'title' => $article->title,
            'cover' => $article->cover,
            'summary' => $article->summary,
            'category_id' => $article->category_id,
            'owner_id' => $article->owner_id,
            'create_time' => $article->create_time,
            'tags' => $article->tags,
            'category' => $category,
            'owner' => $owner,
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'comment_count' => $article->comment_count,
            'favorite_count' => $article->favorite_count,
        ];
    }

}
