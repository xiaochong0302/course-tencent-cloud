<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Repos\Page as PageRepo;

class Page extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Page
     * @throws NotFoundException
     */
    public function checkPage($id)
    {
        $pageRepo = new PageRepo();

        $page = $pageRepo->findById($id);

        if (!$page) {
            throw new NotFoundException('page.not_found');
        }

        return $page;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('page.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('page.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('page.content_too_short');
        }

        if ($length > 65535) {
            throw new BadRequestException('page.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('page.invalid_publish_status');
        }

        return $value;
    }

}
