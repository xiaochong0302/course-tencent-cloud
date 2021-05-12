<?php

namespace App\Validators;

use App\Caches\Category as CategoryCache;
use App\Caches\Tag as TagCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Article as ArticleModel;

class ArticleQuery extends Validator
{

    public function checkCategory($id)
    {
        $categoryCache = new CategoryCache();

        $category = $categoryCache->get($id);

        if (!$category) {
            throw new BadRequestException('article_query.invalid_category');
        }

        return $category->id;
    }

    public function checkTag($id)
    {
        $tagCache = new TagCache();

        $tag = $tagCache->get($id);

        if (!$tag) {
            throw new BadRequestException('article_query.invalid_tag');
        }

        return $tag->id;
    }

    public function checkSort($sort)
    {
        $types = ArticleModel::sortTypes();

        if (!isset($types[$sort])) {
            throw new BadRequestException('article_query.invalid_sort');
        }

        return $sort;
    }

}
