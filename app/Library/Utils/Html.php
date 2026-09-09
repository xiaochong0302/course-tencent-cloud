<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Utils;

/**
 * 参考stackoverflow网友回答,并做微调处理
 *
 * @link https://stackoverflow.com/questions/16583676
 */
class Html
{

    /**
     * @var bool
     */
    protected bool $reachedLimit = false;

    /**
     * @var int
     */
    protected int $totalLen = 0;

    /**
     * @var array
     */
    protected array $toRemove = [];

    public static function truncate(string $html, int $maxLen = 30): string
    {
        $prefix = '<?xml encoding="UTF-8">';

        $source = $prefix . $html;

        $dom = new \DOMDocument();

        $dom->loadHTML($source, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $instance = new static();

        $toRemove = $instance->walk($dom, $maxLen);

        // remove any nodes that exceed limit
        foreach ($toRemove as $child) {
            $child->parentNode->removeChild($child);
        }

        $content = str_replace($prefix, '', $dom->saveHTML());

        return html_entity_decode($content);
    }

    protected function walk(\DOMNode $node, int $maxLen): array
    {
        if ($this->reachedLimit) {
            $this->toRemove[] = $node;
        } else {
            // only text nodes should have text,
            // so do the splitting here
            if ($node instanceof \DOMText) {
                $this->totalLen += $nodeLen = mb_strlen($node->nodeValue);
                // use mb_strlen / mb_substr for UTF-8 support
                if ($this->totalLen > $maxLen) {
                    $node->nodeValue = mb_substr($node->nodeValue, 0, $nodeLen - ($this->totalLen - $maxLen)) . '...';
                    $this->reachedLimit = true;
                }
            }

            // if node has children, walk its child elements
            if (isset($node->childNodes)) {
                foreach ($node->childNodes as $child) {
                    $this->walk($child, $maxLen);
                }
            }
        }

        return $this->toRemove;
    }

}
