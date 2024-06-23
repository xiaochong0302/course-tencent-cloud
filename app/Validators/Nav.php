<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Nav as NavModel;
use App\Repos\Nav as NavRepo;
use Phalcon\Text;

class Nav extends Validator
{

    public function checkNav($id)
    {
        $navRepo = new NavRepo();

        $nav = $navRepo->findById($id);

        if (!$nav) {
            throw new BadRequestException('nav.not_found');
        }

        return $nav;
    }

    public function checkParent($parentId)
    {
        $navRepo = new NavRepo();

        $nav = $navRepo->findById($parentId);

        if (!$nav || $nav->deleted == 1) {
            throw new BadRequestException('nav.parent_not_found');
        }

        return $nav;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('nav.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('nav.name_too_long');
        }

        return $value;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('nav.invalid_priority');
        }

        return $value;
    }

    public function checkUrl($url)
    {
        $value = $this->filter->sanitize($url, ['trim']);

        $case1 = Text::startsWith($value, '/');
        $case2 = Text::startsWith($value, '#');
        $case3 = CommonValidator::url($value);

        if (!$case1 && !$case2 && !$case3) {
            throw new BadRequestException('nav.invalid_url');
        }

        return $value;
    }

    public function checkTarget($target)
    {
        $list = NavModel::targetTypes();

        if (!array_key_exists($target, $list)) {
            throw new BadRequestException('nav.invalid_target');
        }

        return $target;
    }

    public function checkPosition($position)
    {
        $list = NavModel::posTypes();

        if (!array_key_exists($position, $list)) {
            throw new BadRequestException('nav.invalid_position');
        }

        return $position;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('nav.invalid_publish_status');
        }

        return $status;
    }

    public function checkDeleteAbility(NavModel $nav)
    {
        $navRepo = new NavRepo();

        $navs = $navRepo->findAll([
            'parent_id' => $nav->id,
            'deleted' => 0,
        ]);

        if ($navs->count() > 0) {
            throw new BadRequestException('nav.has_child_node');
        }
    }

}
