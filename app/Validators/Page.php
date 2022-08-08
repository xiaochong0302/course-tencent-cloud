<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\MaxPageId as MaxPageIdCache;
use App\Caches\Page as PageCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Page as PageModel;
use App\Repos\Page as PageRepo;

class Page extends Validator
{

    /**
     * @param int $id
     * @return PageModel
     * @throws BadRequestException
     */
    public function checkPageCache($id)
    {
        $this->checkId($id);

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

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxPageIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('page.not_found');
        }
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

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

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
