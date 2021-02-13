<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;


class UserContact extends Model
{

    /**
     * 用户编号（主键）
     *
     * @var int
     */
    public $user_id = 0;

    /**
     * 姓名
     *
     * @var string
     */
    public $name = '';

    /**
     * 手机
     *
     * @var string
     */
    public $phone = '';

    /**
     * 地址（省）
     *
     * @var string
     */
    public $add_province = '';

    /**
     * 地址（市）
     *
     * @var string
     */
    public $add_city = '';

    /**
     * 地址（区）
     *
     * @var string
     */
    public $add_county = '';

    /**
     * 地址（详）
     *
     * @var string
     */
    public $add_other = '';

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    public function getSource(): string
    {
        return 'kg_user_contact';
    }

    public function initialize()
    {
        parent::initialize();

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeSave()
    {
        if (empty($this->create_time)) {
            $this->create_time = time();
        }

        $this->update_time = time();
    }

    public function fullAddress()
    {
        return implode(' ', [
            $this->add_province,
            $this->add_city,
            $this->add_county,
            $this->add_other,
        ]);
    }

}