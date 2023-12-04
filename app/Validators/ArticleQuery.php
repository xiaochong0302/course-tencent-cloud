<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Article as ArticleModel;
use App\Models\Category as CategoryModel;

class ArticleQuery extends Validator
{

    public function checkCategory($id)
    {
        $validator = new Category();

        $category = $validator->checkCategoryCache($id);

        if (!$category) {
            throw new BadRequestException('article_query.invalid_category');
        }

        if ($category->type != CategoryModel::TYPE_ARTICLE) {
            throw new BadRequestException('article_query.invalid_category');
        }

        return $category;
    }

    public function checkTag($id)
    {
        $validator = new Tag();

        $tag = $validator->checkTagCache($id);

        if (!$tag) {
            throw new BadRequestException('article_query.invalid_tag');
        }

        return $tag;
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
