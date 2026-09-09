<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library;

class Seo
{

    /**
     * @var string
     */
    public string $title = '';

    /**
     * @var string
     */
    public string $keywords = '';

    /**
     * @var string
     */
    public string $description = '';

    /**
     * @var string
     */
    protected string $titleSeparator = ' - ';

    public function appendTitle(array|string $text): void
    {
        $append = is_array($text) ? implode($this->titleSeparator, $text) : $text;

        $this->title = $this->title . $this->titleSeparator . $append;
    }

    public function prependTitle(array|string $text): void
    {
        $prepend = is_array($text) ? implode($this->titleSeparator, $text) : $text;

        $this->title = $prepend . $this->titleSeparator . $this->title;
    }

}
