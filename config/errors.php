<?php

$error = [];

/**
 * 系统相关
 */
$error['sys.unauthorized'] = '认证失败';
$error['sys.forbidden'] = '拒绝访问';
$error['sys.bad_request'] = '无效的请求';
$error['sys.not_found'] = '资源不存在';
$error['sys.internal_server_error'] = '内部错误';
$error['sys.service_unavailable'] = '服务不可用';
$error['sys.unknown_error'] = '未知错误';

/**
 * 安全相关
 */
$error['security.too_many_requests'] = '请求过于频繁';
$error['security.invalid_csrf_token'] = '无效的CSRF令牌';
$error['security.invalid_http_referer'] = '无效请求来源';

/**
 * 验证相关
 */
$error['verify.invalid_email'] = '无效的邮箱';
$error['verify.invalid_phone'] = '无效手机号';
$error['verify.invalid_code'] = '无效的验证码';
$error['verify.send_sms_failed'] = '发送短信验证码失败';
$error['verify.send_email_failed'] = '发送邮件验证码失败';

/**
 * captcha相关
 */
$error['captcha.invalid_code'] = '无效的验证码';

/**
 * 帐号相关
 */
$error['account.not_found'] = '账号不存在';
$error['account.login_locked'] = '账号被锁定，无法登录';
$error['account.login_name_incorrect'] = '登录账号不正确';
$error['account.login_password_incorrect'] = '登录密码不正确';
$error['account.invalid_email'] = '无效的电子邮箱';
$error['account.invalid_phone'] = '无效的手机号';
$error['account.invalid_password'] = '无效的密码（字母或数字6-16位）';
$error['account.email_taken'] = '邮箱被占用';
$error['account.phone_taken'] = '手机号被占用';
$error['account.origin_password_incorrect'] = '原密码不正确';

/**
 * 用户相关
 */
$error['user.not_found'] = '用户不存在';
$error['user.name_taken'] = '用户名被占用';
$error['user.title_too_long'] = '头衔过长（超过30个字符）';
$error['user.about_too_long'] = '简介过长（超过255个字符）';
$error['user.invalid_gender'] = '无效的性别类型';
$error['user.invalid_edu_role'] = '无效的教学角色';
$error['user.invalid_admin_role'] = '无效的后台角色';
$error['user.invalid_vip_status'] = '无效的会员状态';
$error['user.invalid_vip_expiry_time'] = '无效的会员期限';
$error['user.invalid_lock_status'] = '无效的锁定状态';
$error['user.invalid_lock_expiry_time'] = '无效的锁定期限';

/**
 * 分类相关
 */
$error['category.not_found'] = '分类不存在';
$error['category.parent_not_found'] = '父级分类不存在';
$error['category.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['category.invalid_publish_status'] = '无效的发布状态';
$error['category.name_too_short'] = '名称太短（少于2个字符）';
$error['category.name_too_long'] = '名称太长（多于30个字符）';
$error['category.has_child_node'] = '不允许相关操作（存在子节点）';

/**
 * 导航相关
 */
$error['nav.not_found'] = '导航不存在';
$error['nav.parent_not_found'] = '父级分类不存在';
$error['nav.invalid_url'] = '无效的访问地址';
$error['nav.invalid_position'] = '无效的位置类型';
$error['nav.invalid_target'] = '无效的目标类型';
$error['nav.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['nav.invalid_publish_status'] = '无效的发布状态';
$error['nav.name_too_short'] = '名称太短（少于2个字符）';
$error['nav.name_too_long'] = '名称太长（多于30个字符）';
$error['nav.has_child_node'] = '不允许相关操作（存在子节点）';

/**
 * 课程相关
 */
$error['course.not_found'] = '课程不存在';
$error['course.title_too_short'] = '标题太短（少于5个字符）';
$error['course.title_too_long'] = '标题太长（多于50个字符）';
$error['course.summary_too_long'] = '标题太长（多于255个字符）';
$error['course.keywords_too_long'] = '关键字太长（多于100个字符）';
$error['course.details_too_long'] = '详情太长（多于3000个字符）';
$error['course.invalid_model'] = '无效的模型类别';
$error['course.invalid_level'] = '无效的难度级别';
$error['course.invalid_cover'] = '无效的封面';
$error['course.invalid_market_price'] = '无效的市场价格（范围：0-10000）';
$error['course.invalid_vip_price'] = '无效的会员价格（范围：0-10000）';
$error['course.invalid_compare_price'] = '无效的比较定价（会员价格高于市场价格）';
$error['course.invalid_study_expiry'] = '无效的学习期限';
$error['course.invalid_refund_expiry'] = '无效的退款期限';
$error['course.invalid_publish_status'] = '无效的发布状态';
$error['course.pub_chapter_not_found'] = '尚未发现已发布的课时';
$error['course.pub_chapter_not_enough'] = '已发布的课时太少（小于30%）';

/**
 * 话题相关
 */
$error['topic.not_found'] = '话题不存在';
$error['topic.title_too_short'] = '标题太短（少于2个字符）';
$error['topic.title_too_long'] = '标题太长（多于50个字符）';
$error['topic.summary_too_long'] = '简介太长（多于255个字符）';
$error['topic.invalid_publish_status'] = '无效的发布状态';

/**
 * 套餐相关
 */
$error['package.not_found'] = '套餐不存在';
$error['package.title_too_short'] = '标题太短（少于5个字符）';
$error['package.title_too_long'] = '标题太长（多于50个字符）';
$error['package.summary_too_long'] = '简介太长（多于255个字符）';
$error['package.invalid_market_price'] = '无效的市场价格';
$error['package.invalid_vip_price'] = '无效的会员价格';
$error['package.invalid_publish_status'] = '无效的发布状态';

/**
 * 课程成员
 */
$error['course_user.not_found'] = '课程学员不存在';
$error['course_user.course_not_found'] = '课程不存在';
$error['course_user.user_not_found'] = '用户不存在';
$error['course_user.apply_not_allowed'] = '当前不允许申请课程';
$error['course_user.has_joined_course'] = '已经加入当前课程';
$error['course_user.invalid_expiry_time'] = '无效的过期时间';

/**
 * 章节相关
 */
$error['chapter.not_found'] = '章节不存在';
$error['chapter.invalid_free_status'] = '无效的免费状态';
$error['chapter.invalid_course_id'] = '无效的课程编号';
$error['chapter.invalid_parent_id'] = '无效的父级编号';
$error['chapter.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['chapter.invalid_publish_status'] = '无效的发布状态';
$error['chapter.title_too_short'] = '标题太短（少于2个字符）';
$error['chapter.title_too_long'] = '标题太长（多于30个字符）';
$error['chapter.summary_too_long'] = '简介太长（多于255个字符）';
$error['chapter.vod_not_ready'] = '点播资源尚未就绪';
$error['chapter.live_not_start'] = '直播尚未开始';
$error['chapter.live_time_empty'] = '直播时间尚未设置';
$error['chapter.read_not_ready'] = '文章内容尚未就绪';
$error['chapter.has_child_node'] = '不允许相关操作（存在子节点）';

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
$error['chapter_read.not_found'] = '文章不存在';
$error['chapter_read.content_too_short'] = '文章内容太短（少于10个字符）';
$error['chapter_read.content_too_long'] = '文章内容太长（多于65535个字符）';

/**
 * 评价相关
 */
$error['review.not_found'] = '评价不存在';
$error['review.course_not_found'] = '课程不存在';
$error['review.invalid_rating'] = '无效的评分（范围：1-5）';
$error['review.invalid_publish_status'] = '无效的发布状态';
$error['review.content_too_short'] = '评价内容太短（少于5个字符）';
$error['review.content_too_long'] = '评价内容太长（多于255个字符）';

/**
 * 咨询相关
 */
$error['consult.not_found'] = '咨询不存在';
$error['consult.course_not_found'] = '课程不存在';
$error['consult.invalid_publish_status'] = '无效的发布状态';
$error['consult.question_too_short'] = '提问太短（少于5个字符）';
$error['consult.question_too_long'] = '提问太长（多于1000个字符）';
$error['consult.answer_too_short'] = '回复太短（少于5个字符）';
$error['consult.answer_too_long'] = '回复太长（多于1000个字符）';

/**
 * 评论相关
 */
$error['comment.not_found'] = '评价不存在';
$error['comment.course_not_found'] = '课程不存在';
$error['comment.chapter_not_found'] = '章节不存在';
$error['comment.invalid_publish_status'] = '无效的发布状态';
$error['comment.content_too_short'] = '评价太短（少于1个字符）';
$error['comment.content_too_long'] = '评价太长（多于1000个字符）';

/**
 * 单页相关
 */
$error['page.not_found'] = '单页不存在';
$error['page.title_too_short'] = '标题太短（少于2个字符）';
$error['page.title_too_long'] = '标题太长（多于50个字符）';
$error['page.content_too_short'] = '内容太短（少于10个字符）';
$error['page.content_too_long'] = '内容太长（多于3000个字符）';
$error['page.invalid_publish_status'] = '无效的发布状态';

/**
 * 帮助相关
 */
$error['help.not_found'] = '帮助不存在';
$error['help.title_too_short'] = '标题太短（少于2个字符）';
$error['help.title_too_long'] = '标题太长（多于50个字符）';
$error['help.content_too_short'] = '内容太短（少于10个字符）';
$error['help.content_too_long'] = '内容太长（多于3000个字符）';
$error['help.invalid_priority'] = '无效的排序数值（范围：1-255）';
$error['help.invalid_publish_status'] = '无效的发布状态';

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
$error['slide.summary_too_long'] = '简介太长（多于255个字符）';
$error['slide.course_not_found'] = '课程不存在';
$error['slide.course_not_published'] = '课程尚未发布';
$error['slide.page_not_found'] = '单页不存在';
$error['slide.page_not_published'] = '单页尚未发布';
$error['slide.invalid_publish_status'] = '无效的发布状态';

/**
 * 订单相关
 */
$error['order.not_found'] = '订单不存在';
$error['order.item_not_found'] = '商品不存在';
$error['order.close_not_allowed'] = '当前不允许关闭订单';
$error['order.has_bought_course'] = '已经够买过该课程';
$error['order.has_bought_package'] = '已经够买过该套餐';
$error['order.trade_expired'] = '交易已过期';

/**
 * 交易相关
 */
$error['trade.not_found'] = '交易不存在';
$error['trade.invalid_channel'] = '无效的平台类型';
$error['trade.invalid_close_action'] = '当前不允许关闭交易';
$error['trade.invalid_refund_action'] = '当前不允许交易退款';
$error['trade.refund_existed'] = '退款申请已经存在';

/**
 * 退款相关
 */
$error['refund.not_found'] = '退款不存在';
$error['refund.apply_note_too_short'] = '退款原因太短（少于2个字符）';
$error['refund.apply_note_too_long'] = '退款原因太长（多于255个字符）';
$error['refund.review_note_too_short'] = '审核备注太短（少于2个字符）';
$error['refund.review_note_too_long'] = '审核备注太长（多于255个字符）';
$error['refund.review_not_allowed'] = '当前不允许审核退款';
$error['refund.invalid_review_status'] = '无效的审核状态';

/**
 * 角色相关
 */
$error['role.not_found'] = '角色不存在';
$error['role.name_too_short'] = '名称太短（少于2个字符）';
$error['role.name_too_long'] = '名称太长（超过30个字符）';
$error['role.summary_too_long'] = '描述太长（超过255个字符）';
$error['role.routes_required'] = '角色权限不能为空';

/**
 * 用户限额
 */
$error['user_daily_limit.reach_favorite_limit'] = '超出日收藏限额';
$error['user_daily_limit.reach_comment_limit'] = '超出日评论限额';
$error['user_daily_limit.reach_consult_limit'] = '超出日咨询限额';
$error['user_daily_limit.reach_review_limit'] = '超出日评价限额';
$error['user_daily_limit.reach_order_limit'] = '超出日订单限额';
$error['user_daily_limit.reach_vote_limit'] = '超出日投票限额';

/**
 * 课程查询
 */
$error['course_query.invalid_top_category'] = '无效的方向类别';
$error['course_query.invalid_sub_category'] = '无效的分类类别';
$error['course_query.invalid_model'] = '无效的模型类别';
$error['course_query.invalid_level'] = '无效的难度类别';
$error['course_query.invalid_sort'] = '无效的排序类别';

return $error;
