<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Article;

use App\Models\Article as ArticleModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Article as ArticleValidator;

class ArticleUpdate extends LogicService
{

    use ArticleTrait;
    use ArticleDataTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $article = $this->checkArticle($id);

        $validator = new ArticleValidator();

        $validator->checkIfAllowEdit($article);

        $data = $this->handlePostData($post);

        if ($article->published == ArticleModel::PUBLISH_REJECTED) {
            $data['published'] = ArticleModel::PUBLISH_PENDING;
        }

        $article->update($data);

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($article, $post['xm_tag_ids']);
        }

        $this->saveDynamicAttrs($article);

        $this->eventsManager->fire('Article:afterUpdate', $this, $article);

        return $article;
    }

}
