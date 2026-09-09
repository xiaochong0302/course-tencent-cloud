<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Caches\MaxChapterId as MaxChapterIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Chapter extends Model
{

    /**
     * 转码模式
     */
    const string TRANS_MODE_STANDARD = 'standard'; // 标准转码
    const string TRANS_MODE_ENCRYPT = 'encrypt'; // 加密转码
    const string TRANS_MODE_NONE = 'none'; // 暂不转码

    /**
     * 转码状态
     */
    const string TRANS_STATUS_PENDING = 'pending'; // 待启动
    const string TRANS_STATUS_CREATED = 'created'; // 已创建
    const string TRANS_STATUS_PROCESSING = 'processing'; // 转码中
    const string TRANS_STATUS_FINISHED = 'finished'; // 已完成
    const string TRANS_STATUS_FAILED = 'failed'; // 已失败

    /**
     * 推流状态
     */
    const string STREAM_STATUS_ACTIVE = 'active'; // 活跃
    const string STREAM_STATUS_INACTIVE = 'inactive'; // 静默
    const string STREAM_STATUS_FORBID = 'forbid'; // 禁播

    /**
     * @var array
     *
     * 点播扩展属性
     */
    protected array $_vod_attrs = [
        'duration' => 0,
        'transcode' => [
            'standard' => ['status' => self::TRANS_STATUS_PENDING],
            'encrypt' => ['status' => self::TRANS_STATUS_PENDING],
        ],
    ];

    /**
     * @var array
     *
     * 直播扩展属性
     */
    protected array $_live_attrs = [
        'start_time' => 0,
        'end_time' => 0,
        'stream' => ['status' => self::STREAM_STATUS_INACTIVE],
        'playback' => ['ready' => 0, 'duration' => 0],
    ];

    /**
     * @var array
     *
     * 图文扩展属性
     */
    protected array $_read_attrs = [
        'duration' => 0,
        'word_count' => 0,
    ];

    /**
     * @var array
     *
     * 面授扩展属性
     */
    protected array $_offline_attrs = [
        'start_time' => 0,
        'end_time' => 0,
    ];

    /**
     * @var array
     *
     * 文档扩展属性
     */
    protected array $_doc_attrs = [
        'format' => 'html',
        'size' => 0,
    ];

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 父级编号
     *
     * @var int
     */
    public int $parent_id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * 摘要
     *
     * @var string
     */
    public string $summary = '';

    /**
     * 关键字
     *
     * @var string
     */
    public string $keywords = '';

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = 100;

    /**
     * 免费标识
     *
     * @var int
     */
    public int $free = 0;

    /**
     * 模式类型
     *
     * @var int
     */
    public int $model = 0;

    /**
     * 扩展属性
     *
     * @var array|string
     */
    public array|string $attrs = [];

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 课时数
     *
     * @var int
     */
    public int $lesson_count = 0;

    /**
     * 学员数
     *
     * @var int
     */
    public int $user_count = 0;

    /**
     * 评论数
     *
     * @var int
     */
    public int $comment_count = 0;

    /**
     * 点赞数
     *
     * @var int
     */
    public int $like_count = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_chapter');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        /**
         * @var Course $course
         */
        $course = Course::findFirst($this->course_id);

        if (empty($this->model)) {
            $this->model = $course->model;
        }

        if ($this->parent_id > 0) {
            if (empty($this->attrs)) {
                if ($this->model == Course::MODEL_VOD) {
                    $this->attrs = $this->_vod_attrs;
                } elseif ($this->model == Course::MODEL_LIVE) {
                    $this->attrs = $this->_live_attrs;
                } elseif ($this->model == Course::MODEL_READ) {
                    $this->attrs = $this->_read_attrs;
                } elseif ($this->model == Course::MODEL_OFFLINE) {
                    $this->attrs = $this->_offline_attrs;
                } elseif ($this->model == Course::MODEL_DOC) {
                    $this->attrs = $this->_doc_attrs;
                }
            }
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->update_time = time();
    }

    public function afterCreate(): void
    {
        $cache = new MaxChapterIdCache();

        $cache->rebuild();

        if ($this->parent_id > 0) {

            $data = [
                'course_id' => $this->course_id,
                'chapter_id' => $this->id,
            ];

            switch ($this->model) {
                case Course::MODEL_VOD:
                    $vod = new ChapterVod();
                    $vod->assign($data);
                    $vod->create();
                    break;
                case Course::MODEL_LIVE:
                    $live = new ChapterLive();
                    $live->assign($data);
                    $live->create();
                    break;
                case Course::MODEL_READ:
                    $read = new ChapterRead();
                    $read->assign($data);
                    $read->create();
                    break;
                case Course::MODEL_OFFLINE:
                    $offline = new ChapterOffline();
                    $offline->assign($data);
                    $offline->create();
                    break;
                case Course::MODEL_DOC:
                    $doc = new ChapterDoc();
                    $doc->assign($data);
                    $doc->create();
                    break;
            }
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->attrs)) {
            $this->attrs = json_decode($this->attrs, true);
        }
    }

    public static function transModeTypes(): array
    {
        return [
            self::TRANS_MODE_STANDARD => '标准转码',
            self::TRANS_MODE_ENCRYPT => '加密转码',
            self::TRANS_MODE_NONE => '暂不转码',
        ];
    }

}
