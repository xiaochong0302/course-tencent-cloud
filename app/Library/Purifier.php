<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

use HTMLPurifier;
use HTMLPurifier_Config;

class Purifier
{

    protected HTMLPurifier $purifier;

    protected array $options = [
        'CSS.AllowedProperties' => 'color,font-size,text-align,background-color',
        'AutoFormat.AutoParagraph' => true,
        'AutoFormat.RemoveEmpty' => true,
        'HTML.TargetBlank' => true,
    ];

    public function __construct(array $options = [])
    {
        $options = array_merge($this->options, $options);

        $config = $this->getConfig($options);

        $this->purifier = new HTMLPurifier($config);
    }

    public function clean(string $html): string
    {
        return $this->purifier->purify($html);
    }

    public function cleanArray(array $html): array
    {
        return $this->purifier->purifyArray($html);
    }

    protected function getConfig(array $options): HTMLPurifier_Config
    {
        $config = HTMLPurifier_Config::createDefault();

        foreach ($options as $key => $value) {
            $config->set($key, $value);
        }

        $serializerPath = cache_path('purifier');

        if (!file_exists($serializerPath)) {
            mkdir($serializerPath, 0777);
        }

        $config->set('Cache.SerializerPath', $serializerPath);

        return $config;
    }

}
