<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library;

use Phalcon\Cache\Cache;
use Phalcon\Di\Di;
use Phalcon\Support\Helper\Str\Random;
use Phalcon\Support\HelperFactory;

class Captcha
{

    /**
     * @var Cache
     */
    protected Cache $cache;

    public function __construct()
    {
        $this->cache = Di::getDefault()->get('cache');
    }

    public function generate(): array
    {
        $ticket = $this->getRandTicket();

        $cacheKey = $this->getCacheKey($ticket);

        $expression = $this->getExpression();

        $this->cache->set($cacheKey, $expression['result'], 60);

        $width = 100;
        $height = 25;

        $im = imagecreate($width, $height);

        $white = imagecolorallocate($im, 255, 255, 255);
        $gray = imagecolorallocate($im, 118, 151, 199);
        $bgColor = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));

        imagefilledrectangle($im, 0, 0, $width, $height, $bgColor);

        for ($i = 0; $i < 200; $i++) {
            imagesetpixel($im, rand(0, $width), rand(0, $height), $gray);
        }

        imagestring($im, 5, 5, 4, $expression['num1'], $white);
        imagestring($im, 5, 30, 3, $expression['operator'], $white);
        imagestring($im, 5, 45, 4, $expression['num2'], $white);
        imagestring($im, 5, 70, 3, '=', $white);
        imagestring($im, 5, 85, 3, '?', $white);

        ob_start();

        imagepng($im);

        $content = ob_get_clean();

        imagedestroy($im);

        return [
            'ticket' => $ticket,
            'content' => $this->base64Encode($content),
        ];
    }

    public function check(string $ticket, string $rand): bool
    {
        if (!$ticket || !$rand) {
            return false;
        }

        $key = $this->getCacheKey($ticket);

        $content = $this->cache->get($key);

        return $content == $rand;
    }

    public function clear(string $ticket): void
    {
        $key = $this->getCacheKey($ticket);

        $this->cache->delete($key);
    }

    protected function getExpression(): array
    {
        $operators = ['+', '-', '*', '/'];

        $index = array_rand($operators);

        $operator = $operators[$index];

        switch ($operator) {
            case '+':
                $num1 = rand(10, 50);
                $num2 = rand(10, 50);
                $result = $num1 + $num2;
                break;
            case '-':
                $num1 = rand(50, 100);
                $num2 = rand(10, 50);
                $result = $num1 - $num2;
                break;
            case '*':
                $num1 = rand(1, 10);
                $num2 = rand(1, 10);
                $result = $num1 * $num2;
                break;
            default:
                $multiple = rand(2, 10);
                $num1 = $multiple * rand(1, 10);
                $num2 = $multiple;
                $result = $num1 / $num2;
                break;
        }

        return [
            'num1' => $num1,
            'num2' => $num2,
            'operator' => $operator,
            'result' => $result,
        ];
    }

    protected function base64Encode(string $content): string
    {
        return sprintf('data:image/png;base64,%s', base64_encode($content));
    }

    protected function getRandTicket(): string
    {
        $helper = new HelperFactory();

        return $helper->random(Random::RANDOM_ALNUM, 16);
    }

    protected function getCacheKey($key): string
    {
        return "captcha-{$key}";
    }

}
