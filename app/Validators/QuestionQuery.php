<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\Tag as TagCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Question as QuestionModel;

class QuestionQuery extends Validator
{

    public function checkCategory($id)
    {
        $validator = new Category();

        $category = $validator->checkCategoryCache($id);

        if (!$category) {
            throw new BadRequestException('question_query.invalid_category');
        }

        return $category->id;
    }

    public function checkTag($id)
    {
        $tagCache = new TagCache();

        $tag = $tagCache->get($id);

        if (!$tag) {
            throw new BadRequestException('question_query.invalid_tag');
        }

        return $tag->id;
    }

    public function checkSort($sort)
    {
        $types = QuestionModel::sortTypes();

        if (!isset($types[$sort])) {
            throw new BadRequestException('question_query.invalid_sort');
        }

        return $sort;
    }

}
