<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseList as CourseListBuilder;
use App\Caches\Course as CourseCache;
use App\Caches\CourseCategoryList as CourseCategoryListCache;
use App\Caches\CourseRelatedList as CourseRelatedListCache;
use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Models\CourseCategory as CourseCategoryModel;
use App\Models\CourseRating as CourseRatingModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseCategory as CourseCategoryRepo;
use App\Repos\CourseRelated as CourseRelatedRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\User as UserRepo;
use App\Services\Sync\CourseIndex as CourseIndexSync;
use App\Validators\Course as CourseValidator;
use App\Validators\CourseOffline as CourseOfflineValidator;

class Course extends Service
{

    public function getCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        if (!empty($params['xm_category_ids'])) {
            $params['category_id'] = explode(',', $params['xm_category_ids']);
        }

        if (!empty($params['xm_teacher_ids'])) {
            $params['teacher_id'] = explode(',', $params['xm_teacher_ids']);
        }

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

            $courseRating = new CourseRatingModel();

            $courseRating->course_id = $course->id;

            if ($courseRating->create() === false) {
                throw new \RuntimeException('Create CourseRating Failed');
            }

            $imGroup = new ImGroupModel();

            $imGroup->type = ImGroupModel::TYPE_COURSE;
            $imGroup->course_id = $course->id;
            $imGroup->name = $course->title;
            $imGroup->about = $course->summary;

            if ($imGroup->create() === false) {
                throw new \RuntimeException('Create ImGroup Failed');
            }

            $this->db->commit();

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

        if (isset($post['origin_price'])) {
            $data['origin_price'] = $validator->checkOriginPrice($post['origin_price']);
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

        if (isset($post['xm_category_ids'])) {
            $this->saveCategories($course, $post['xm_category_ids']);
        }

        if (isset($post['xm_teacher_ids'])) {
            $this->saveTeachers($course, $post['xm_teacher_ids']);
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

        $this->updateImGroup($course);

        return $course;
    }

    public function deleteCourse($id)
    {
        $course = $this->findOrFail($id);
        $course->deleted = 1;
        $course->update();

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);
        $group->deleted = 1;
        $group->update();

        return $course;
    }

    public function restoreCourse($id)
    {
        $course = $this->findOrFail($id);
        $course->deleted = 0;
        $course->update();

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);
        $group->deleted = 0;
        $group->update();

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

    public function getStudyExpiryOptions()
    {
        return CourseModel::studyExpiryOptions();
    }

    public function getRefundExpiryOptions()
    {
        return CourseModel::refundExpiryOptions();
    }

    public function getXmCategories($id)
    {
        $categoryRepo = new CategoryRepo();

        $allCategories = $categoryRepo->findAll([
            'type' => CategoryModel::TYPE_COURSE,
            'published' => 1,
        ]);

        if ($allCategories->count() == 0) return [];

        $courseCategoryIds = [];

        if ($id > 0) {
            $courseRepo = new CourseRepo();
            $courseCategories = $courseRepo->findCategories($id);
            if ($courseCategories->count() > 0) {
                foreach ($courseCategories as $category) {
                    $courseCategoryIds[] = $category->id;
                }
            }
        }

        $list = [];

        /**
         * 没有二级分类的不显示
         */
        foreach ($allCategories as $category) {
            if ($category->level == 1 && $category->child_count > 0) {
                $list[$category->id] = [
                    'name' => $category->name,
                    'value' => $category->id,
                    'children' => [],
                ];
            }
        }

        foreach ($allCategories as $category) {
            $selected = in_array($category->id, $courseCategoryIds);
            $parentId = $category->parent_id;
            if ($category->level == 2) {
                $list[$parentId]['children'][] = [
                    'name' => $category->name,
                    'value' => $category->id,
                    'selected' => $selected,
                ];
            }
        }

        return array_values($list);
    }

    public function getXmTeachers($id)
    {
        $userRepo = new UserRepo();

        $allTeachers = $userRepo->findTeachers();

        if ($allTeachers->count() == 0) return [];

        $courseTeacherIds = [];

        if ($id > 0) {
            $courseRepo = new CourseRepo();
            $courseTeachers = $courseRepo->findTeachers($id);
            if ($courseTeachers->count() > 0) {
                foreach ($courseTeachers as $teacher) {
                    $courseTeacherIds[] = $teacher->id;
                }
            }
        }

        $list = [];

        foreach ($allTeachers as $teacher) {
            $selected = in_array($teacher->id, $courseTeacherIds);
            $list[] = [
                'name' => $teacher->name,
                'value' => $teacher->id,
                'selected' => $selected,
            ];
        }

        return $list;
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

        $items = $courseRepo->findAll(['published' => 1]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s（¥%0.2f）', $item->title, $item->market_price),
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

    protected function findOrFail($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
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

    protected function saveTeachers(CourseModel $course, $teacherIds)
    {
        $courseRepo = new CourseRepo();

        $courseTeachers = $courseRepo->findTeachers($course->id);

        $originTeacherIds = [];

        if ($courseTeachers->count() > 0) {
            foreach ($courseTeachers as $teacher) {
                $originTeacherIds[] = $teacher->id;
            }
        }

        $newTeacherIds = $teacherIds ? explode(',', $teacherIds) : [];
        $addedTeacherIds = array_diff($newTeacherIds, $originTeacherIds);

        if ($addedTeacherIds) {
            foreach ($addedTeacherIds as $teacherId) {
                $courseTeacher = new CourseUserModel();
                $courseTeacher->course_id = $course->id;
                $courseTeacher->user_id = $teacherId;
                $courseTeacher->role_type = CourseUserModel::ROLE_TEACHER;
                $courseTeacher->source_type = CourseUserModel::SOURCE_IMPORT;
                $courseTeacher->create();
            }
        }

        $deletedTeacherIds = array_diff($originTeacherIds, $newTeacherIds);

        if ($deletedTeacherIds) {
            $courseUserRepo = new CourseUserRepo();
            foreach ($deletedTeacherIds as $teacherId) {
                $courseTeacher = $courseUserRepo->findCourseTeacher($course->id, $teacherId);
                if ($courseTeacher) {
                    $courseTeacher->delete();
                }
            }
        }

        $teacherId = $newTeacherIds[0] ?? 0;

        if ($teacherId) {
            $course->teacher_id = $teacherId;
            $course->update();
        }

        $cache = new CourseTeacherListCache();

        $cache->rebuild($course->id);
    }

    protected function saveCategories(CourseModel $course, $categoryIds)
    {
        $courseRepo = new CourseRepo();

        $courseCategories = $courseRepo->findCategories($course->id);

        $originCategoryIds = [];

        if ($courseCategories->count() > 0) {
            foreach ($courseCategories as $category) {
                $originCategoryIds[] = $category->id;
            }
        }

        $newCategoryIds = $categoryIds ? explode(',', $categoryIds) : [];
        $addedCategoryIds = array_diff($newCategoryIds, $originCategoryIds);

        if ($addedCategoryIds) {
            foreach ($addedCategoryIds as $categoryId) {
                $courseCategory = new CourseCategoryModel();
                $courseCategory->course_id = $course->id;
                $courseCategory->category_id = $categoryId;
                $courseCategory->create();
            }
        }

        $deletedCategoryIds = array_diff($originCategoryIds, $newCategoryIds);

        if ($deletedCategoryIds) {
            $courseCategoryRepo = new CourseCategoryRepo();
            foreach ($deletedCategoryIds as $categoryId) {
                $courseCategory = $courseCategoryRepo->findCourseCategory($course->id, $categoryId);
                if ($courseCategory) {
                    $courseCategory->delete();
                }
            }
        }

        $categoryId = $newCategoryIds[0] ?? 0;

        if ($categoryId) {
            $course->category_id = $categoryId;
            $course->update();
        }

        $cache = new CourseCategoryListCache();

        $cache->rebuild($course->id);
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

    protected function updateImGroup(CourseModel $course)
    {
        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);

        $data = [];

        if ($course->title != $group->name) {
            $data['name'] = $course->title;
        }

        if ($course->published != $group->published) {
            $data['published'] = $course->published;
        }

        if ($course->teacher_id > 0 && $group->owner_id == 0) {

            $groupUser = new ImGroupUserModel();
            $groupUser->group_id = $group->id;
            $groupUser->user_id = $course->teacher_id;
            $groupUser->create();

            $data['owner_id'] = $course->teacher_id;
            $data['user_count'] = $group->user_count + 1;
        }

        $group->update($data);
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
