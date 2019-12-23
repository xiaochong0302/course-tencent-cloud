<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\CourseCategory as CourseCategoryModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseCategory as CourseCategoryRepo;
use App\Repos\CourseRelated as CourseRelatedRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\User as UserRepo;
use App\Transformers\CourseList as CourseListTransformer;
use App\Validators\Course as CourseValidator;

class Course extends Service
{

    public function getCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        if (isset($params['xm_category_ids'])) {
            $params['id'] = $this->getCategoryCourseIds($params['xm_category_ids']);
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
        $course = $this->findOrFail($id);

        return $course;
    }

    public function createCourse()
    {
        $post = $this->request->getPost();

        $validator = new CourseValidator();

        $data = [];

        $data['model'] = $validator->checkModel($post['model']);
        $data['title'] = $validator->checkTitle($post['title']);
        $data['published'] = 0;

        $course = new CourseModel();

        $course->create($data);

        $this->eventsManager->fire('course:afterCreate', $this, $course);

        return $course;
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

        if (isset($post['price_mode'])) {
            if ($post['price_mode'] == 'free') {
                $data['market_price'] = 0;
                $data['vip_price'] = 0;
            } else {
                $data['market_price'] = $validator->checkMarketPrice($post['market_price']);
                $data['vip_price'] = $validator->checkVipPrice($post['vip_price']);
                $data['expiry'] = $validator->checkExpiry($post['expiry']);
            }
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

        $course->update($data);

        $this->eventsManager->fire('course:afterUpdate', $this, $course);

        return $course;
    }

    public function deleteCourse($id)
    {
        $course = $this->findOrFail($id);

        if ($course->deleted == 1) {
            return false;
        }

        $course->deleted = 1;

        $course->update();

        $this->eventsManager->fire('course:afterDelete', $this, $course);

        return $course;
    }

    public function restoreCourse($id)
    {
        $course = $this->findOrFail($id);

        if ($course->deleted == 0) {
            return false;
        }

        $course->deleted = 0;

        $course->update();

        $this->eventsManager->fire('course:afterRestore', $this, $course);

        return $course;
    }

    public function getXmCategories($id)
    {
        $categoryRepo = new CategoryRepo();

        $allCategories = $categoryRepo->findAll(['deleted' => 0]);

        if ($allCategories->count() == 0) {
            return [];
        }

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

        foreach ($allCategories as $category) {
            if ($category->level == 1) {
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
                    'id' => $category->id,
                    'name' => $category->name,
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

        if ($allTeachers->count() == 0) {
            return [];
        }

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
                'id' => $teacher->id,
                'name' => $teacher->name,
                'selected' => $selected,
            ];
        }

        return $list;
    }

    public function getXmCourses($id)
    {
        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findRelatedCourses($id);

        $list = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $list[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'selected' => true,
                ];
            }
        }

        return $list;
    }

    public function getChapters($id)
    {
        $course = $this->findOrFail($id);

        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findAll([
            'parent_id' => 0,
            'course_id' => $course->id,
            'deleted' => $deleted,
        ]);

        return $chapters;
    }

    protected function findOrFail($id)
    {
        $validator = new CourseValidator();

        $result = $validator->checkCourse($id);

        return $result;
    }

    protected function saveTeachers($course, $teacherIds)
    {
        $courseRepo = new CourseRepo();

        $courseTeachers = $courseRepo->findTeachers($course->id);

        $originTeacherIds = [];

        if ($courseTeachers->count() > 0) {
            foreach ($courseTeachers as $teacher) {
                $originTeacherIds[] = $teacher->id;
            }
        }

        $newTeacherIds = explode(',', $teacherIds);
        $addedTeacherIds = array_diff($newTeacherIds, $originTeacherIds);

        if ($addedTeacherIds) {
            foreach ($addedTeacherIds as $teacherId) {
                $courseTeacher = new CourseUserModel();
                $courseTeacher->create([
                    'course_id' => $course->id,
                    'user_id' => $teacherId,
                    'role_type' => CourseUserModel::ROLE_TEACHER,
                    'source_type' => CourseUserModel::SOURCE_IMPORT,
                    'expire_time' => strtotime('+10 years'),
                ]);
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
    }

    protected function saveCategories($course, $categoryIds)
    {
        $courseRepo = new CourseRepo();

        $courseCategories = $courseRepo->findCategories($course->id);

        $originCategoryIds = [];

        if ($courseCategories->count() > 0) {
            foreach ($courseCategories as $category) {
                $originCategoryIds[] = $category->id;
            }
        }

        $newCategoryIds = explode(',', $categoryIds);
        $addedCategoryIds = array_diff($newCategoryIds, $originCategoryIds);

        if ($addedCategoryIds) {
            foreach ($addedCategoryIds as $categoryId) {
                $courseCategory = new CourseCategoryModel();
                $courseCategory->create([
                    'course_id' => $course->id,
                    'category_id' => $categoryId,
                ]);
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
    }

    protected function saveRelatedCourses($course, $courseIds)
    {
        $courseRepo = new CourseRepo();

        $relatedCourses = $courseRepo->findRelatedCourses($course->id);

        $originRelatedIds = [];

        if ($relatedCourses->count() > 0) {
            foreach ($relatedCourses as $relatedCourse) {
                $originRelatedIds[] = $relatedCourse->id;
            }
        }

        $newRelatedIds = explode(',', $courseIds);
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
                        $courseRelated->create([
                            'course_id' => $course->id,
                            'related_id' => $relatedId,
                        ]);
                    }
                    $record = $courseRelatedRepo->findCourseRelated($relatedId, $course->id);
                    if (!$record) {
                        $courseRelated = new CourseRelatedModel();
                        $courseRelated->create([
                            'course_id' => $relatedId,
                            'related_id' => $course->id,
                        ]);
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
    }

    protected function getCategoryCourseIds($categoryIds)
    {
        if (empty($categoryIds)) return [];

        $courseCategoryRepo = new CourseCategoryRepo();

        $categoryIds = explode(',', $categoryIds);

        $relations = $courseCategoryRepo->findByCategoryIds($categoryIds);

        $result = [];

        if ($relations->count() > 0) {
            foreach ($relations as $relation) {
                $result[] = $relation->course_id;
            }
        }

        return $result;
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items > 0) {

            $transformer = new CourseListTransformer();

            $pipeA = $pager->items->toArray();
            $pipeB = $transformer->handleCourses($pipeA);
            $pipeC = $transformer->handleCategories($pipeB);
            $pipeD = $transformer->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
