<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

trait ContentTrait
{

    protected function handleContent(string $content): string
    {
        $content = $this->handleCosImageStyle($content);

        return $this->handleOutLink($content);
    }

    private function handleCosImageStyle(string $content): string
    {
        $style = '!content_800';

        $pattern = '/src="(.*?)\/img\/content\/(.*?)"/';

        $replacement = 'src="$1/img/content/$2' . $style . '"';

        return preg_replace($pattern, $replacement, $content);
    }

    private function handleOutLink(string $content): string
    {
        $site = kg_setting('site');

        return preg_replace_callback('/href="(.*?)"/', function ($matches) use ($site) {
            $siteHost = parse_url($site['url'], PHP_URL_HOST);
            if (str_starts_with($matches[1], 'http')) {
                $outHost = parse_url($matches[1], PHP_URL_HOST);
                if ($siteHost != $outHost) {
                    return sprintf('href="/link?url=%s" target="_blank"', urlencode($matches[1]));
                }
            }
            return sprintf('href="%s"', $matches[1]);
        }, $content);
    }

}
