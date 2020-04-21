<?php

namespace App\Validators;

use App\Caches\MaxPageId as MaxPageIdCache;
use App\Caches\Page as PageCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Page as PageRepo;

class Page extends Validator
{

    public function checkPageCache($id)
    {
        $id = intval($id);

        $maxPageIdCache = new MaxPageIdCache();

        $maxPageId = $maxPageIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxPageId) {
            throw new BadRequestException('page.not_found');
        }

        $pageCache = new PageCache();

        $page = $pageCache->get($id);

        if (!$page) {
            throw new BadRequestException('page.not_found');
        }

        return $page;
    }

    public function checkPage($id)
    {
        $pageRepo = new PageRepo();

        $page = $pageRepo->findById($id);

        if (!$page) {
            throw new BadRequestException('page.not_found');
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

        if ($length > 3000) {
            throw new BadRequestException('page.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('page.invalid_publish_status');
        }

        return $status;
    }

}
