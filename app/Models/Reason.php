<?php

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
            109 => '抄袭他人作品',
            110 => '涉嫌非法集资',
            111 => '其它问题',
        ];
    }

}