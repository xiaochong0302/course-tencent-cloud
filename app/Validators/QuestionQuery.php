<?php

namespace App\Validators;

use App\Caches\Tag as TagCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Question as QuestionModel;

class QuestionQuery extends Validator
{

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
