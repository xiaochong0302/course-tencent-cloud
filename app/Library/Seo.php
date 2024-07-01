<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

class Seo
{

    /**
     * @var string 标题
     */
    protected $title = '';

    /**
     * @var string 关键字
     */
    protected $keywords = '';

    /**
     * @var string 描述
     */
    protected $description = '';

    /**
     * @var string 标题分隔符
     */
    protected $titleSeparator = ' - ';

    /**
     * @var string 关键字分隔符
     */
    protected $keywordSeparator = ',';

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $keywords = implode($this->keywordSeparator, $keywords);
        }

        $this->keywords = $keywords;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setTitleSeparator($titleSeparator)
    {
        $this->titleSeparator = $titleSeparator;
    }

    public function appendTitle($text)
    {
        $append = is_array($text) ? implode($this->titleSeparator, $text) : $text;

        $this->title = $this->title . $this->titleSeparator . $append;
    }

    public function prependTitle($text)
    {
        $prepend = is_array($text) ? implode($this->titleSeparator, $text) : $text;

        $this->title = $prepend . $this->titleSeparator . $this->title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTitleSeparator()
    {
        return $this->titleSeparator;
    }

    public function getKeywordSeparator()
    {
        return $this->keywordSeparator;
    }

}
