<?php

$error = [];

/**
 * 通用相关
 */
$error['sys.uri_not_found'] = '资源地址不存在';
$error['sys.invalid_auth_token'] = '无效的auth_token';
$error['sys.invalid_referer'] = '无效的请求来源';
$error['sys.auth_user_failed'] = '用户认证失败';
$error['sys.access_denied'] = '访问被拒绝';
$error['sys.session_expired'] = '会话已过期';
$error['sys.unknown_error'] = '未知错误';

/**
 * 帐号相关
 */
$error['user.not_found'] = '用户不存在';
$error['user.login_locked'] = '账户已被锁定，无法登录';
$error['user.login_account_incorrect'] = '登录账户不正确';
$error['user.login_password_incorrect'] = '登录密码不正确';
$error['user.title_too_long'] = '头衔过长（超过30个字符）';
$error['user.about_too_long'] = '简介过长（超过255个字符）';
$error['user.invalid_email'] = '无效的电子邮箱';
$error['user.invalid_phone'] = '无效的手机号';
$error['user.invalid_password'] = '无效的密码（字母或数字6-16位）';
$error['user.invalid_edu_role'] = '无效的教学角色';
$error['user.invalid_admin_role'] = '无效的后台角色';
$error['user.invalid_lock_status'] = '无效的锁定状态';
$error['user.invalid_lock_expiry'] = '无效的锁定期限';
$error['user.invalid_captcha_code'] = '无效的验证码';
$error['user.invalid_verify_code'] = '无效的验证码';
$error['user.email_taken'] = '邮箱被占用';
$error['user.phone_taken'] = '手机号被占用';
$error['user.name_taken'] = '用户名被占用';
$error['user.origin_password_incorrect'] = '原密码不正确';
$error['user.confirm_password_incorrect'] = '确认密码不正确';
$error['user.admin_not_authorized'] = '账户没有登录后台的授权';

/**
 * 分类相关
 */
$error['category.not_found'] = '分类不存在';
$error['category.parent_not_found'] = '父级分类不存在';
$error['category.invalid_publish_status'] = '无效的发布状态';
$error['category.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['category.name_too_short'] = '名称太短（少于2个字符）';
$error['category.name_too_long'] = '名称太长（多于30个字符）';

/**
 * 课程相关
 */
$error['course.not_found'] = '课程不存在';
$error['course.title_too_short'] = '标题太短（少于5个字符）';
$error['course.title_too_long'] = '标题太长（多于30个字符）';
$error['course.invalid_category_id'] = '无效的分类编号';
$error['course.invalid_model'] = '无效的模型类别';
$error['course.invalid_level'] = '无效的难度级别';
$error['course.invalid_cover'] = '无效的封面';
$error['course.invalid_market_price'] = '无效的市场价格';
$error['course.invalid_vip_price'] = '无效的会员价格';
$error['course.invalid_expiry'] = '无效的期限';
$error['course.invalid_publish_status'] = '无效的发布状态';
$error['course.pub_chapter_not_found'] = '尚未发现已发布的课时';
$error['course.pub_chapter_too_few'] = '已发布的课时太少（未过三分之一）';

$error['course.has_not_favorited'] = '尚未收藏该课程';
$error['course.has_favorited'] = '已经收藏过该课程了';
$error['course.has_applied'] = '已经参加过该课程了';
$error['course.has_not_applied'] = '尚未参加该课程';
$error['course.has_reviewed'] = '已经评价过该课程了';
$error['course.apply_offline_course'] = '申请未发布的课程';
$error['course.apply_charge_course'] = '申请非免费的课程';

/**
 * 套餐相关
 */
$error['package.not_found'] = '课程不存在';
$error['package.title_too_short'] = '标题太短（少于5个字符）';
$error['package.title_too_long'] = '标题太长（多于30个字符）';
$error['package.invalid_market_price'] = '无效的市场价格';
$error['package.invalid_vip_price'] = '无效的会员价格';
$error['package.invalid_publish_status'] = '无效的发布状态';

/**
 * 课程学员
 */
$error['course_student.not_found'] = '课程学员不存在';
$error['course_student.course_not_found'] = '课程不存在';
$error['course_student.user_not_found'] = '用户不存在';
$error['course_student.user_has_joined'] = '课程学员已存在';
$error['course_student.invalid_expire_time'] = '无效的过期时间';
$error['course_student.invalid_lock_status'] = '无效的锁定状态';

/**
 * 章节相关
 */
$error['chapter.not_found'] = '章节不存在';
$error['chapter.invalid_publish_status'] = '无效的发布状态';
$error['chapter.invalid_free_status'] = '无效的免费状态';
$error['chapter.invalid_course_id'] = '无效的课程编号';
$error['chapter.invalid_parent_id'] = '无效的父级编号';
$error['chapter.title_too_short'] = '标题太短（少于2个字符）';
$error['chapter.title_too_long'] = '标题太长（多于30个字符）';
$error['chapter.vod_not_uploaded'] = '点播资源文件尚未上传';
$error['chapter.vod_not_translated'] = '点播资源转码尚未完成';
$error['chapter.live_not_started'] = '直播尚未开始';
$error['chapter.live_time_empty'] = '直播时间尚未设置';
$error['chapter.article_content_empty'] = '文章内容尚未设置';

/**
 * 点播相关
 */
$error['chapter_vod.not_found'] = '点播资源不存在';
$error['chapter_vod.invalid_file_id'] = '无效的文件编号';

/**
 * 直播相关
 */
$error['chapter_live.not_found'] = '直播资源不存在';
$error['chapter_live.invalid_start_time'] = '无效的开始时间';
$error['chapter_live.invalid_end_time'] = '无效的结束时间';
$error['chapter_live.start_lt_now'] = '开始时间小于当前时间';
$error['chapter_live.end_lt_now'] = '结束时间小于当前时间';
$error['chapter_live.start_gt_end'] = '开始时间大于结束时间';
$error['chapter_live.time_too_long'] = '直播时间太长（超过3小时）';

/**
 * 图文相关
 */
$error['chapter_article.not_found'] = '文章不存在';
$error['chapter_article.content_too_short'] = '文章内容太短（少于10个字符）';
$error['chapter_article.content_too_long'] = '文章内容太长（多于65535个字符）';

/**
 * 评价相关
 */
$error['review.not_found'] = '评价不存在';
$error['review.course_not_found'] = '课程不存在';
$error['review.invalid_publish_status'] = '无效的发布状态';
$error['review.content_too_short'] = '评价太短（少于5个字符）';
$error['review.content_too_long'] = '评价太长（多于255个字符）';

/**
 * 单页相关
 */
$error['page.not_found'] = '单页不存在';
$error['page.title_too_short'] = '标题太短（少于2个字符）';
$error['page.title_too_long'] = '标题太长（多于30个字符）';
$error['page.content_too_short'] = '内容太短（少于10个字符）';
$error['page.content_too_long'] = '内容太长（多于65535个字符）';
$error['page.invalid_publish_status'] = '无效的发布状态';

/**
 * 轮播相关
 */
$error['slide.not_found'] = '轮播不存在';
$error['slide.invalid_target'] = '无效的目标类型';
$error['slide.invalid_link'] = '无效的链接地址';
$error['slide.invalid_priority'] = '无效的排序数值（范围：1-255）';
$error['slide.invalid_cover'] = '无效的封面图片';
$error['slide.title_too_short'] = '标题太短（少于2个字符）';
$error['slide.title_too_long'] = '标题太长（多于50个字符）';
$error['slide.course_not_found'] = '课程不存在';
$error['slide.course_not_published'] = '课程尚未发布';
$error['slide.page_not_found'] = '单页不存在';
$error['slide.page_not_published'] = '单页尚未发布';
$error['slide.invalid_start_time'] = '无效的开始时间';
$error['slide.invalid_end_time'] = '无效的结束时间';
$error['slide.invalid_time_range'] = '无效的时间范围';
$error['slide.invalid_publish_status'] = '无效的发布状态';

/**
 * 订单相关
 */
$error['order.not_found'] = '订单不存在';
$error['order.close_not_allowed'] = '当前不允许关闭订单';
$error['order.has_bought_course'] = '已经够买过该课程';
$error['order.has_bought_package'] = '已经够买过该套餐';
$error['order.trade_expired'] = '交易已过期';

/**
 * 交易相关
 */
$error['trade.not_found'] = '交易不存在';
$error['trade.close_not_allowed'] = '当前不允许关闭交易';
$error['trade.refund_not_allowed'] = '当前不允许交易退款';
$error['trade.refund_existed'] = '退款申请已经存在，请前往退款管理查看';

/**
 * 退款相关
 */
$error['refund.not_found'] = '退款不存在';
$error['trade.review_not_allowed'] = '当前不允许审核退款';
$error['trade.invalid_review_status'] = '无效的审核状态';

/**
 * 角色相关
 */
$error['role.not_found'] = '角色不存在';
$error['role.name_too_short'] = '名称太短（少于2个字符）';
$error['role.name_too_long'] = '名称太长（超过30个字符）';
$error['role.routes_required'] = '角色权限不能为空';

return $error;
