<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Help as HelpRepo;
use App\Services\EditorStorage as EditorStorageService;

class Help extends Validator
{

    public function checkHelp($id)
    {
        $helpRepo = new HelpRepo();

        $help = $helpRepo->findById($id);

        if (!$help) {
            throw new BadRequestException('help.not_found');
        }

        return $help;
    }

    public function checkCategoryId($id)
    {
        $result = 0;

        if ($id > 0) {
            $validator = new Category();
            $category = $validator->checkCategory($id);
            $result = $category->id;
        }

        return $result;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('help.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('help.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $storage = new EditorStorageService();

        $value = $storage->handle($value);

        $length = kg_editor_content_length($value);

        if ($length < 10) {
            throw new BadRequestException('help.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('help.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('help.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('help.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('help.invalid_publish_status');
        }

        return $status;
    }

}
