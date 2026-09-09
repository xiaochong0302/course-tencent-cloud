<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Upload extends Model
{

    /**
     * 资源类型
     */
    const int TYPE_COVER_IMG = 1; // 封面图
    const int TYPE_CONTENT_IMG = 2; // 内容图
    const int TYPE_AVATAR_IMG = 3; // 头像
    const int TYPE_RESOURCE = 4; // 课件资源
    const int TYPE_DOC_FILE = 5; // 文档文件
    const int TYPE_IM_FILE = 6; // IM文件（已弃用）
    const int TYPE_ICON_IMG = 7; // 图标
    const int TYPE_INVOICE_FILE = 8; // 发票
    const int TYPE_EXAM_QUESTION_IMG = 9; // 试题图片
    const int TYPE_EXAM_ANSWER_FILE = 10; // 答案文件
    const int TYPE_DEFAULT_IMG = 99; // 默认图片

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 名称
     *
     * @var string
     */
    public string $name = '';

    /**
     * 路径
     *
     * @var string
     */
    public string $path = '';

    /**
     * mime
     *
     * @var string
     */
    public string $mime = '';

    /**
     * md5
     *
     * @var string
     */
    public string $md5 = '';

    /**
     * 大小（字节）
     *
     * @var int
     */
    public int $size = 0;

    /**
     * 类型
     *
     * @var int
     */
    public int $type = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

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

        $this->setSource('kg_upload');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

}
