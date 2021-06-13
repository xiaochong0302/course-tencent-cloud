<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Article as ArticleModel;
use Phalcon\Events\Event as PhEvent;

class Article extends Listener
{

    public function afterCreate(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterUpdate(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterDelete(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterRestore(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterApprove(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterReject(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterView(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterFavorite(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterUndoFavorite(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterLike(PhEvent $event, $source, ArticleModel $article)
    {

    }

    public function afterUndoLike(PhEvent $event, $source, ArticleModel $article)
    {

    }

}