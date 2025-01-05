<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Repos\Page as PageRepo;
use App\Services\EditorStorage as EditorStorageService;

class Page extends Validator
{

    public function checkPage($id)
    {
        $pageRepo = new PageRepo();

        if (CommonValidator::intNumber($id)) {
            $page = $pageRepo->findById($id);
        } else {
            $page = $pageRepo->findByAlias($id);
        }

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

    public function checkAlias($alias)
    {
        $value = $this->filter->sanitize($alias, ['trim', 'string']);

        $value = str_replace(['/', '?', '#'], '', $value);

        $length = kg_strlen($value);

        if (CommonValidator::intNumber($value)) {
            throw new BadRequestException('page.invalid_alias');
        }

        if ($length < 2) {
            throw new BadRequestException('page.alias_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('page.alias_too_long');
        }

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('page.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $storage = new EditorStorageService();

        $value = $storage->handle($value);

        $length = kg_editor_content_length($value);

        if ($length < 10) {
            throw new BadRequestException('page.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('page.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('page.invalid_publish_status');
        }

        return $status;
    }

    public function checkIfAliasTaken($alias)
    {
        $pageRepo = new PageRepo();

        $page = $pageRepo->findByAlias($alias);

        if ($page) {
            throw new BadRequestException('page.alias_taken');
        }
    }

}
