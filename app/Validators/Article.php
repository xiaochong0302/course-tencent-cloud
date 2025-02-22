<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Article as ArticleModel;
use App\Repos\Article as ArticleRepo;
use App\Services\EditorStorage as EditorStorageService;

class Article extends Validator
{

    public function checkArticle($id)
    {
        $articleRepo = new ArticleRepo();

        $article = $articleRepo->findById($id);

        if (!$article) {
            throw new BadRequestException('article.not_found');
        }

        return $article;
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
            throw new BadRequestException('article.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('article.title_too_long');
        }

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('article.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('article.summary_too_long');
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
            throw new BadRequestException('article.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('article.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('article.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkSourceType($type)
    {
        if (!array_key_exists($type, ArticleModel::sourceTypes())) {
            throw new BadRequestException('article.invalid_source_type');
        }

        return (int)$type;
    }

    public function checkSourceUrl($url)
    {
        $url = $this->filter->sanitize($url, ['trim', 'string']);

        if (!CommonValidator::url($url)) {
            throw new BadRequestException('article.invalid_source_url');
        }

        return $url;
    }

    public function checkFeatureStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('article.invalid_feature_status');
        }

        return $status;
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, ArticleModel::publishTypes())) {
            throw new BadRequestException('article.invalid_publish_status');
        }

        return $status;
    }

    public function checkCloseStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('article.invalid_close_status');
        }

        return $status;
    }

    public function checkIfAllowEdit(ArticleModel $article)
    {
        $approved = $article->published == ArticleModel::PUBLISH_APPROVED;

        if ($approved) {
            throw new BadRequestException('article.edit_not_allowed');
        }
    }

}
