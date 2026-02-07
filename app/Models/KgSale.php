<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class KgSale
{

    /**
     * 物品类型
     */
    const ITEM_COURSE = 1; // 课程服务
    const ITEM_PACKAGE = 2; // 课程套餐
    const ITEM_VIP = 3; // 会员套餐
    const ITEM_EXAM_PAPER = 4; // 考试服务
    const ITEM_ARTICLE = 5; // 专栏文章
    const ITEM_PAY_ACCOUNT_VERIFY = 98; // 账户验证
    const ITEM_PAY_TEST = 99; // 支付测试
    const ITEM_GOODS = 100; // 实物商品

}
