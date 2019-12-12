<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Library\Validator\Common as CommonValidator;
use App\Models\Nav as NavModel;
use App\Repos\Nav as NavRepo;
use Phalcon\Text;

class Nav extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Nav
     * @throws NotFoundException
     */
    public function checkNav($id)
    {
        $navRepo = new NavRepo();

        $nav = $navRepo->findById($id);

        if (!$nav) {
            throw new NotFoundException('nav.not_found');
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

        $stageA = Text::startsWith($value, '/');
        $stageB = CommonValidator::url($value);

        if (!$stageA && !$stageB) {
            throw new BadRequestException('nav.invalid_url');
        }

        return $value;
    }

    public function checkTarget($target)
    {
        $value = $this->filter->sanitize($target, ['trim']);

        $scopes = NavModel::targets();

        if (!isset($scopes[$value])) {
            throw new BadRequestException('nav.invalid_target');
        }

        return $value;
    }

    public function checkPosition($position)
    {
        $value = $this->filter->sanitize($position, ['trim']);

        $scopes = NavModel::positions();

        if (!isset($scopes[$value])) {
            throw new BadRequestException('nav.invalid_position');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('nav.invalid_publish_status');
        }

        return $value;
    }

}
