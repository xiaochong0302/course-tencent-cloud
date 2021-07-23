<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\QuestionTag as QuestionTagModel;
use App\Models\User as UserModel;
use App\Repos\QuestionTag as QuestionTagRepo;
use App\Repos\Tag as TagRepo;
use App\Traits\Client as ClientTrait;
use App\Validators\Question as QuestionValidator;

trait QuestionDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new QuestionValidator();

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);

        if (isset($post['closed'])) {
            $data['closed'] = $validator->checkCloseStatus($post['closed']);
        }

        if (isset($post['anonymous'])) {
            $data['anonymous'] = $validator->checkAnonymousStatus($post['anonymous']);
        }

        return $data;
    }

    protected function getPublishStatus(UserModel $user)
    {
        return $user->question_count > 100 ? QuestionModel::PUBLISH_APPROVED : QuestionModel::PUBLISH_PENDING;
    }

    protected function saveDynamicAttrs(QuestionModel $question)
    {
        $question->cover = kg_parse_first_content_image($question->content);

        $question->summary = kg_parse_summary($question->content);

        $question->update();

        /**
         * 重新执行afterFetch
         */
        $question->afterFetch();
    }

    protected function saveTags(QuestionModel $question, $tagIds)
    {
        $originTagIds = [];

        /**
         * 修改数据后，afterFetch设置的属性会失效，重新执行
         */
        $question->afterFetch();

        if ($question->tags) {
            $originTagIds = kg_array_column($question->tags, 'id');
        }

        $newTagIds = $tagIds ? explode(',', $tagIds) : [];
        $addedTagIds = array_diff($newTagIds, $originTagIds);

        if ($addedTagIds) {
            foreach ($addedTagIds as $tagId) {
                $questionTag = new QuestionTagModel();
                $questionTag->question_id = $question->id;
                $questionTag->tag_id = $tagId;
                $questionTag->create();
                $this->recountTagQuestions($tagId);
            }
        }

        $deletedTagIds = array_diff($originTagIds, $newTagIds);

        if ($deletedTagIds) {
            $questionTagRepo = new QuestionTagRepo();
            foreach ($deletedTagIds as $tagId) {
                $questionTag = $questionTagRepo->findQuestionTag($question->id, $tagId);
                if ($questionTag) {
                    $questionTag->delete();
                    $this->recountTagQuestions($tagId);
                }
            }
        }

        $questionTags = [];

        if ($newTagIds) {
            $tagRepo = new TagRepo();
            $tags = $tagRepo->findByIds($newTagIds);
            if ($tags->count() > 0) {
                $questionTags = [];
                foreach ($tags as $tag) {
                    $questionTags[] = ['id' => $tag->id, 'name' => $tag->name];
                    $this->recountTagQuestions($tag->id);
                }
            }
        }

        $question->tags = $questionTags;

        $question->update();

        /**
         * 重新执行afterFetch
         */
        $question->afterFetch();
    }

    protected function recountTagQuestions($tagId)
    {
        $tagRepo = new TagRepo();

        $tag = $tagRepo->findById($tagId);

        if (!$tag) return;

        $questionCount = $tagRepo->countQuestions($tagId);

        $tag->question_count = $questionCount;

        $tag->update();
    }

}
