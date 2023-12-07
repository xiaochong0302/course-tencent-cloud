<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Question as QuestionModel;

class QuestionQuery extends Validator
{

    public function checkCategory($id)
    {
        $validator = new Category();

        return $validator->checkCategoryCache($id);
    }

    public function checkTag($id)
    {
        $validator = new Tag();

        return $validator->checkTagCache($id);
    }

    public function checkUser($id)
    {
        $validator = new User();

        return $validator->checkUserCache($id);
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
