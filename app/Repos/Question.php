<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Answer as AnswerModel;
use App\Models\Comment as CommentModel;
use App\Models\Question as QuestionModel;
use App\Models\QuestionFavorite as QuestionFavoriteModel;
use App\Models\QuestionLike as QuestionLikeModel;
use App\Models\QuestionTag as QuestionTagModel;
use App\Models\Tag as TagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Question extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(QuestionModel::class);

        $builder->where('1 = 1');

        $fakeId = false;

        if (!empty($where['tag_id'])) {
            $where['id'] = $this->getTagQuestionIds($where['tag_id']);
            $fakeId = empty($where['id']);
        }

        /**
         * 构造空记录条件
         */
        if ($fakeId) $where['id'] = -999;

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $builder->inWhere('id', $where['id']);
            } else {
                $builder->andWhere('id = :id:', ['id' => $where['id']]);
            }
        }

        if (!empty($where['category_id'])) {
            if (is_array($where['category_id'])) {
                $builder->inWhere('category_id', $where['category_id']);
            } else {
                $builder->andWhere('category_id = :category_id:', ['category_id' => $where['category_id']]);
            }
        }

        if (!empty($where['owner_id'])) {
            if (is_array($where['owner_id'])) {
                $builder->inWhere('owner_id', $where['owner_id']);
            } else {
                $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
            }
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $builder->inWhere('published', $where['published']);
            } else {
                $builder->andWhere('published = :published:', ['published' => $where['published']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (isset($where['anonymous'])) {
            $builder->andWhere('anonymous = :anonymous:', ['anonymous' => $where['anonymous']]);
        }

        if (isset($where['closed'])) {
            $builder->andWhere('closed = :closed:', ['closed' => $where['closed']]);
        }

        if (isset($where['featured'])) {
            $builder->andWhere('featured = :featured:', ['featured' => $where['featured']]);
        }

        if (isset($where['solved'])) {
            $builder->andWhere('solved = :solved:', ['solved' => $where['solved']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if ($sort == 'featured') {
            $builder->andWhere('featured = 1');
        } elseif ($sort == 'unanswered') {
            $builder->andWhere('answer_count = 0');
        } elseif ($sort == 'reported') {
            $builder->andWhere('report_count > 0');
        }

        switch ($sort) {
            case 'active':
                $orderBy = 'last_reply_time DESC, id DESC';
                break;
            case 'score':
                $orderBy = 'score DESC, id DESC';
                break;
            case 'oldest':
                $orderBy = 'id ASC';
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
     * @return QuestionModel|Model|bool
     */
    public function findById($id)
    {
        return QuestionModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return QuestionModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $questionId
     * @return ResultsetInterface|Resultset|TagModel[]
     */
    public function findTags($questionId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('t.*')
            ->addFrom(TagModel::class, 't')
            ->join(QuestionTagModel::class, 't.id = qt.tag_id', 'qt')
            ->where('qt.question_id = :question_id:', ['question_id' => $questionId])
            ->andWhere('t.published = 1')
            ->andWhere('t.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $questionId
     * @param int $userId
     * @return ResultsetInterface|Resultset|AnswerModel[]
     */
    public function findUserAnswers($questionId, $userId)
    {
        return AnswerModel::query()
            ->where('question_id = :question_id:', ['question_id' => $questionId])
            ->andWhere('owner_id = :owner_id:', ['owner_id' => $userId])
            ->execute();
    }

    public function countQuestions()
    {
        return (int)QuestionModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => QuestionModel::PUBLISH_APPROVED],
        ]);
    }

    public function countAnswers($questionId)
    {
        return (int)AnswerModel::count([
            'conditions' => 'question_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $questionId, 2 => AnswerModel::PUBLISH_APPROVED],
        ]);
    }

    public function countComments($questionId)
    {
        return (int)CommentModel::count([
            'conditions' => 'item_id = ?1 AND item_type = ?2 AND published = ?3 AND deleted = 0',
            'bind' => [1 => $questionId, 2 => CommentModel::ITEM_QUESTION, 3 => CommentModel::PUBLISH_APPROVED],
        ]);
    }

    public function countFavorites($questionId)
    {
        return (int)QuestionFavoriteModel::count([
            'conditions' => 'question_id = :question_id: AND deleted = 0',
            'bind' => ['question_id' => $questionId],
        ]);
    }

    public function countLikes($questionId)
    {
        return (int)QuestionLikeModel::count([
            'conditions' => 'question_id = :question_id: AND deleted = 0',
            'bind' => ['question_id' => $questionId],
        ]);
    }

    protected function getTagQuestionIds($tagId)
    {
        $tagIds = is_array($tagId) ? $tagId : [$tagId];

        $repo = new QuestionTag();

        $rows = $repo->findByTagIds($tagIds);

        $result = [];

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'question_id');
        }

        return $result;
    }

}
