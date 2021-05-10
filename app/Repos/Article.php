<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Article as ArticleModel;
use App\Models\ArticleFavorite as ArticleFavoriteModel;
use App\Models\ArticleLike as ArticleLikeModel;
use App\Models\ArticleTag as ArticleTagModel;
use App\Models\Comment as CommentModel;
use App\Models\Tag as TagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Article extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ArticleModel::class);

        $builder->where('1 = 1');

        if (!empty($where['tag_id'])) {
            $where['id'] = $this->getTagArticleIds($where['tag_id']);
        }

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $builder->inWhere('id', $where['id']);
            } else {
                $builder->andWhere('id = :id:', ['id' => $where['id']]);
            }
        }

        if (!empty($where['category_id'])) {
            $builder->andWhere('category_id = :category_id:', ['category_id' => $where['category_id']]);
        }

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (isset($where['source_type'])) {
            $builder->andWhere('source_type = :source_type:', ['source_type' => $where['source_type']]);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (isset($where['private'])) {
            $builder->andWhere('private = :private:', ['private' => $where['private']]);
        }

        if (isset($where['featured'])) {
            $builder->andWhere('featured = :featured:', ['featured' => $where['featured']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if ($sort == 'featured') {
            $builder->andWhere('featured = 1');
        }

        switch ($sort) {
            case 'like':
                $orderBy = 'like_count DESC';
                break;
            case 'popular':
                $orderBy = 'score DESC';
                break;
            case 'comment':
                $orderBy = 'comment_count DESC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return ArticleModel|Model|bool
     */
    public function findById($id)
    {
        return ArticleModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ArticleModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ArticleModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $articleId
     * @return ResultsetInterface|Resultset|TagModel[]
     */
    public function findTags($articleId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('t.*')
            ->addFrom(TagModel::class, 't')
            ->join(ArticleTagModel::class, 't.id = at.tag_id', 'at')
            ->where('at.article_id = :article_id:', ['article_id' => $articleId])
            ->andWhere('t.published = 1')
            ->getQuery()->execute();
    }

    public function countArticles()
    {
        return (int)ArticleModel::count(['conditions' => 'deleted = 0']);
    }

    public function countComments($articleId)
    {
        return (int)CommentModel::count([
            'conditions' => 'item_id = ?1 AND item_type = ?2 AND deleted = 0',
            'bind' => [1 => $articleId, 2 => CommentModel::ITEM_ARTICLE],
        ]);
    }

    public function countLikes($articleId)
    {
        return (int)ArticleLikeModel::count([
            'conditions' => 'article_id = :article_id:',
            'bind' => ['article_id' => $articleId],
        ]);
    }

    public function countFavorites($articleId)
    {
        return (int)ArticleFavoriteModel::count([
            'conditions' => 'article_id = :article_id:',
            'bind' => ['article_id' => $articleId],
        ]);
    }

    protected function getTagArticleIds($tagId)
    {
        $tagIds = is_array($tagId) ? $tagId : [$tagId];

        $repo = new ArticleTag();

        $rows = $repo->findByTagIds($tagIds);

        $result = [];

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'article_id');
        }

        return $result;
    }

}
