<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Reason
{

    public static function articleRejectOptions()
    {
        return [
            101 => '内容质量差',
            102 => '旧闻重提',
            103 => '内容不实',
            104 => '标题夸张',
            105 => '题文不符',
            106 => '低俗色情',
            107 => '广告软文',
            108 => '封面反感',
            109 => '归类与主题不符',
            110 => '抄袭他人作品',
            111 => '内容涉嫌违法',
            112 => '其它问题',
        ];
    }

    public static function questionRejectOptions()
    {
        return [
            101 => '没有讨论价值',
            102 => '错别字，病句过多',
            103 => '内容不实',
            104 => '标题夸张',
            105 => '题文不符',
            106 => '低俗色情',
            107 => '广告软文',
            108 => '恶意对比',
            109 => '涉嫌歧视，恶意抹黑',
            110 => '归类与主题不符',
            111 => '内容涉嫌违法',
            112 => '其它问题',
        ];
    }

    public static function answerRejectOptions()
    {
        return [
            101 => '答非所问',
            102 => '内容质量差',
            103 => '内容不实',
            104 => '低俗色情',
            105 => '广告软文',
            106 => '恶意对比',
            107 => '涉嫌歧视，恶意抹黑',
            108 => '配图引起不适',
            109 => '内容涉嫌违法',
            110 => '其它问题',
        ];
    }

    public static function commentRejectOptions()
    {
        return [
            101 => '广告软文',
            102 => '违法内容',
            103 => '恶意对比',
            104 => '低俗色情',
            105 => '人身攻击',
            106 => '其它问题',
        ];
    }

    public static function reviewRejectOptions()
    {
        return [
            101 => '广告软文',
            102 => '违法内容',
            103 => '恶意对比',
            104 => '低俗色情',
            105 => '人身攻击',
            106 => '其它问题',
        ];
    }

    public static function consultRejectOptions()
    {
        return [
            101 => '广告软文',
            102 => '违法内容',
            103 => '恶意对比',
            104 => '低俗色情',
            105 => '人身攻击',
            106 => '其它问题',
        ];
    }

    public static function reportOptions()
    {
        return [
            '101' => '推广广告：广告、招聘、推广、测试等内容',
            '102' => '违规内容：色情、暴力、血腥、敏感信息等',
            '103' => '违规内容：含有法律、法规禁止的其他内容',
            '104' => '恶意内容：人身攻击、挑衅辱骂、恶意行为',
            '105' => '其它理由：请填写补充说明',
        ];
    }

}
