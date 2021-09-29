<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Validator as AppValidator;

class ArticleClose extends LogicService
{

    use ArticleTrait;

    public function handle($id)
    {
        $article = $this->checkArticle($id);

        $user = $this->getLoginUser();

        $validator = new AppValidator();

        $validator->checkOwner($user->id, $article->owner_id);

        $article->closed = $article->closed == 1 ? 0 : 1;

        $article->update();

        return $article;
    }

}
