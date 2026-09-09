<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library;

class Sitemap
{

    /**
     * 更新频率
     */
    const string FREQ_ALWAYS = 'always';
    const string FREQ_HOURLY = 'hourly';
    const string FREQ_DAILY = 'daily';
    const string FREQ_WEEKLY = 'weekly';
    const string FREQ_MONTHLY = 'monthly';
    const string FREQ_YEARLY = 'yearly';
    const string FREQ_NEVER = 'never';

    /**
     * @var array
     */
    protected array $items = [];

    /**
     * @param string $loc 位置
     * @param float|null $priority 优先级 0-1
     * @param string|null $changefreq 更新频率的单位
     * @param string|null $lastmod 日期格式 YYYY-MM-DD
     */
    public function addItem(string $loc, ?float $priority = null, ?string $changefreq = null, ?string $lastmod = null): void
    {
        $this->items[] = [
            'loc' => $loc,
            'priority' => $priority,
            'changefreq' => $changefreq,
            'lastmod' => $lastmod,
        ];
    }

    public function build(?string $filename = null): string|int|false
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($this->items as $item) {
            $item['loc'] = htmlentities($item['loc'], ENT_QUOTES);
            $xml .= "\t<url>\n\t\t<loc>{$item['loc']}</loc>\n";
            if (!empty($item['lastmod'])) {
                $xml .= "\t\t<lastmod>{$item['lastmod']}</lastmod>\n";
            }
            if (!empty($item['changefreq'])) {
                $xml .= "\t\t<changefreq>{$item['changefreq']}</changefreq>\n";
            }
            if (!empty($item['priority'])) {
                $xml .= "\t\t<priority>{$item['priority']}</priority>\n";
            }
            $xml .= "\t</url>\n";
        }

        $xml .= "</urlset>\n";

        if ($filename !== null) {
            return file_put_contents($filename, $xml);
        }

        return $xml;
    }

}
