<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

class Sitemap
{

    /**
     * 更新频率
     */
    const FREQ_ALWAYS = 'always';
    const FREQ_HOURLY = 'hourly';
    const FREQ_DAILY = 'daily';
    const FREQ_WEEKLY = 'weekly';
    const FREQ_MONTHLY = 'monthly';
    const FREQ_YEARLY = 'yearly';
    const FREQ_NEVER = 'never';

    protected $items = [];

    /**
     * @param string $loc 位置
     * @param string $priority 优先级 0-1
     * @param string $changefreq 更新频率的单位
     * @param string $lastmod 日期格式 YYYY-MM-DD
     */
    public function addItem($loc, $priority = null, $changefreq = null, $lastmod = null)
    {
        $this->items[] = array(
            'loc' => $loc,
            'priority' => $priority,
            'changefreq' => $changefreq,
            'lastmod' => $lastmod,
        );
    }

    /**
     * @param string $filename
     * @return mixed
     */
    public function build($filename = null)
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

        if (!is_null($filename)) {
            return file_put_contents($filename, $xml);
        } else {
            return $xml;
        }
    }

}