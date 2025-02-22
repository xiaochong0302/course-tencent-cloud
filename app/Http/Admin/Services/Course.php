<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseList as CourseListBuilder;
use App\Builders\ResourceList as ResourceListBuilder;
use App\Caches\Course as CourseCache;
use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseTag as CourseTagModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseRelated as CourseRelatedRepo;
use App\Repos\CourseTag as CourseTagRepo;
use App\Repos\Tag as TagRepo;
use App\Repos\User as UserRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Course\XmTagList as XmTagListService;
use App\Services\Sync\CourseIndex as CourseIndexSync;
use App\Validators\Course as CourseValidator;
use App\Validators\CourseOffline as CourseOfflineValidator;

class Course extends Service
{

    public function getCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    public function getCourse($id)
    {
        return $this->findOrFail($id);
    }

    public function createCourse()
    {
        $post = $this->request->getPost();

        $validator = new CourseValidator();

        $model = $validator->checkModel($post['model']);
        $title = $validator->checkTitle($post['title']);

        try {

            $this->db->begin();

            $course = new CourseModel();

            $course->model = $model;
            $course->title = $title;

            if ($course->create() === false) {
                throw new \RuntimeException('Create Course Failed');
            }

            $this->db->commit();

            $this->rebuildCourseCache($course);
            $this->rebuildCourseIndex($course);

            return $course;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('http');

            $logger->error('Create Course Error ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

    public function updateCourse($id)
    {
        $course = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CourseValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['details'])) {
            $data['details'] = $validator->checkDetails($post['details']);
        }

        if (isset($post['level'])) {
            $data['level'] = $validator->checkLevel($post['level']);
        }

        if (isset($post['fake_user_count'])) {
            $data['fake_user_count'] = $validator->checkUserCount($post['fake_user_count']);
        }

        if (isset($post['study_expiry'])) {
            $data['study_expiry'] = $validator->checkStudyExpiry($post['study_expiry']);
        }

        if (isset($post['refund_expiry'])) {
            $data['refund_expiry'] = $validator->checkRefundExpiry($post['refund_expiry']);
        }

        if (isset($post['market_price'])) {
            $data['market_price'] = $validator->checkMarketPrice($post['market_price']);
        }

        if (isset($post['vip_price'])) {
            $data['vip_price'] = $validator->checkVipPrice($post['vip_price']);
        }

        if (isset($post['featured'])) {
            $data['featured'] = $validator->checkFeatureStatus($post['featured']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            if ($post['published'] == 1) {
                $validator->checkPublishAbility($course);
            }
        }

        if (isset($post['category_id'])) {
            $data['category_id'] = $validator->checkCategoryId($post['category_id']);
        }

        if (isset($post['teacher_id'])) {
            $data['teacher_id'] = $validator->checkTeacherId($post['teacher_id']);
        }

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($course, $post['xm_tag_ids']);
        }

        if (isset($post['xm_course_ids'])) {
            $this->saveRelatedCourses($course, $post['xm_course_ids']);
        }

        if ($course->model == CourseModel::MODEL_OFFLINE) {

            $validator = new CourseOfflineValidator();

            $data['study_expiry'] = 0;
            $data['refund_expiry'] = 0;

            if (isset($post['attrs']['start_date']) && isset($post['attrs']['end_date'])) {
                $data['attrs']['start_date'] = $validator->checkStartDate($post['attrs']['start_date']);
                $data['attrs']['end_date'] = $validator->checkEndDate($post['attrs']['end_date']);
                $validator->checkDateRange($data['attrs']['start_date'], $data['attrs']['end_date']);
            }

            if (isset($post['attrs']['user_limit'])) {
                $data['attrs']['user_limit'] = $validator->checkUserLimit($post['attrs']['user_limit']);
            }

            if (isset($post['attrs']['location'])) {
                $data['attrs']['location'] = $validator->checkLocation($post['attrs']['location']);
            }
        }

        $course->update($data);

        $this->rebuildCourseCache($course);
        $this->rebuildCourseIndex($course);

        return $course;
    }

    public function deleteCourse($id)
    {
        $course = $this->findOrFail($id);

        $course->deleted = 1;

        $course->update();

        $this->rebuildCourseCache($course);
        $this->rebuildCourseIndex($course);

        return $course;
    }

    public function restoreCourse($id)
    {
        $course = $this->findOrFail($id);

        $course->deleted = 0;

        $course->update();

        $this->rebuildCourseCache($course);
        $this->rebuildCourseIndex($course);

        return $course;
    }

    public function getModelTypes()
    {
        return CourseModel::modelTypes();
    }

    public function getLevelTypes()
    {
        return CourseModel::levelTypes();
    }

    public function getTeacherOptions()
    {
        $userRepo = new UserRepo();

        $teachers = $userRepo->findTeachers();

        if ($teachers->count() == 0) return [];

        $options = [];

        foreach ($teachers as $teacher) {
            $options[] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
            ];
        }

        return $options;
    }

    public function getCategoryOptions()
    {
        $categoryService = new CategoryService();

        return $categoryService->getCategoryOptions(CategoryModel::TYPE_COURSE);
    }

    public function getStudyExpiryOptions()
    {
        return CourseModel::studyExpiryOptions();
    }

    public function getRefundExpiryOptions()
    {
        return CourseModel::refundExpiryOptions();
    }

    public function getXmTags($id)
    {
        $service = new XmTagListService();

        return $service->handle($id);
    }

    public function getXmCourses($id)
    {
        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findRelatedCourses($id);

        $courseIds = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $courseIds[] = $course->id;
            }
        }

        $items = $courseRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $item->market_price),
                'value' => $item->id,
                'selected' => in_array($item->id, $courseIds),
            ];
        }

        return $result;
    }

    public function getChapters($id)
    {
        $course = $this->findOrFail($id);

        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findAll([
            'parent_id' => 0,
            'course_id' => $course->id,
            'deleted' => $deleted,
        ]);
    }

    public function getResources($id)
    {
        $courseRepo = new CourseRepo();

        $resources = $courseRepo->findResources($id);

        if ($resources->count() == 0) return [];

        $builder = new ResourceListBuilder();

        $items = $resources->toArray();

        $items = $builder->handleUploads($items);

        return $builder->objects($items);
    }

    protected function findOrFail($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    protected function saveTags(CourseModel $course, $tagIds)
    {
        /**
         * 修改数据后，afterFetch设置的属性会失效，重新执行
         */
        $course->afterFetch();

        if (is_string($tagIds) && strlen($tagIds) > 0) {
            $tagIds = explode(',', $tagIds);
        }

        $originTagIds = [];

        if ($course->tags) {
            $originTagIds = kg_array_column($course->tags, 'id');
        }

        $newTagIds = $tagIds ?: [];
        $addedTagIds = array_diff($newTagIds, $originTagIds);

        if ($addedTagIds) {
            foreach ($addedTagIds as $tagId) {
                $courseTag = new CourseTagModel();
                $courseTag->course_id = $course->id;
                $courseTag->tag_id = $tagId;
                $courseTag->create();
                $this->recountTagCourses($tagId);
            }
        }

        $deletedTagIds = array_diff($originTagIds, $newTagIds);

        if ($deletedTagIds) {
            $courseTagRepo = new CourseTagRepo();
            foreach ($deletedTagIds as $tagId) {
                $courseTag = $courseTagRepo->findCourseTag($course->id, $tagId);
                if ($courseTag) {
                    $courseTag->delete();
                    $this->recountTagCourses($tagId);
                }
            }
        }

        $courseTags = [];

        if ($newTagIds) {
            $tagRepo = new TagRepo();
            $tags = $tagRepo->findByIds($newTagIds);
            if ($tags->count() > 0) {
                foreach ($tags as $tag) {
                    $courseTags[] = ['id' => $tag->id, 'name' => $tag->name];
                    $this->recountTagCourses($tag->id);
                }
            }
        }

        $course->tags = $courseTags;

        $course->update();
    }

    protected function saveRelatedCourses(CourseModel $course, $courseIds)
    {
        $courseRepo = new CourseRepo();

        $relatedCourses = $courseRepo->findRelatedCourses($course->id);

        $originRelatedIds = [];

        if ($relatedCourses->count() > 0) {
            foreach ($relatedCourses as $relatedCourse) {
                $originRelatedIds[] = $relatedCourse->id;
            }
        }

        $newRelatedIds = $courseIds ? explode(',', $courseIds) : [];
        $addedRelatedIds = array_diff($newRelatedIds, $originRelatedIds);

        $courseRelatedRepo = new CourseRelatedRepo();

        /**
         * 双向关联
         */
        if ($addedRelatedIds) {
            foreach ($addedRelatedIds as $relatedId) {
                if ($relatedId != $course->id) {
                    $record = $courseRelatedRepo->findCourseRelated($course->id, $relatedId);
                    if (!$record) {
                        $courseRelated = new CourseRelatedModel();
                        $courseRelated->course_id = $course->id;
                        $courseRelated->related_id = $relatedId;
                        $courseRelated->create();
                    }
                    $record = $courseRelatedRepo->findCourseRelated($relatedId, $course->id);
                    if (!$record) {
                        $courseRelated = new CourseRelatedModel();
                        $courseRelated->course_id = $relatedId;
                        $courseRelated->related_id = $course->id;
                        $courseRelated->create();
                    }
                }
            }
        }

        $deletedRelatedIds = array_diff($originRelatedIds, $newRelatedIds);

        /**
         * 单向删除
         */
        if ($deletedRelatedIds) {
            $courseRelatedRepo = new CourseRelatedRepo();
            foreach ($deletedRelatedIds as $relatedId) {
                $courseRelated = $courseRelatedRepo->findCourseRelated($course->id, $relatedId);
                if ($courseRelated) {
                    $courseRelated->delete();
                }
            }
        }

        $cache = new CourseRelatedListCache();

        $cache->rebuild($course->id);
    }

    protected function rebuildCourseCache(CourseModel $course)
    {
        $cache = new CourseCache();

        $cache->rebuild($course->id);
    }

    protected function rebuildCourseIndex(CourseModel $course)
    {
        $sync = new CourseIndexSync();

        $sync->addItem($course->id);
    }

    protected function recountTagCourses($tagId)
    {
        $tagRepo = new TagRepo();

        $tag = $tagRepo->findById($tagId);

        if (!$tag) return;

        $courseCount = $tagRepo->countCourses($tagId);

        $tag->course_count = $courseCount;

        $tag->update();
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCategories($pipeA);
            $pipeC = $builder->handleTeachers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
