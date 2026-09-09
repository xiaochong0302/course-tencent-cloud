<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

$error = [];

/**
 * 系统相关
 */
$error['sys.unauthorized'] = '认证失败';
$error['sys.forbidden'] = '拒绝访问';
$error['sys.bad_request'] = '无效的请求';
$error['sys.not_found'] = '资源不存在';
$error['sys.server_error'] = '服务器内部错误';
$error['sys.service_unavailable'] = '服务不可用';
$error['sys.trans_rollback'] = '事务回滚';
$error['sys.unknown_error'] = '未知错误';

/**
 * 安全相关
 */
$error['security.too_many_requests'] = '请求过于频繁';
$error['security.invalid_csrf_token'] = '无效的CSRF令牌';
$error['security.invalid_http_referer'] = '无效请求来源';
$error['security.client_address_blocked'] = '客户端地址被禁止';
$error['security.contain_sensitive_text'] = '文本疑似包含敏感内容';
$error['security.contain_sensitive_image'] = '图片疑似包含敏感内容';

/**
 * 验证相关
 */
$error['verify.invalid_phone'] = '无效手机号';
$error['verify.invalid_email'] = '无效的邮箱';
$error['verify.invalid_code'] = '无效的验证码';
$error['verify.invalid_sms_code'] = '无效的短信验证码';
$error['verify.invalid_mail_code'] = '无效的邮件验证码';
$error['verify.send_sms_failed'] = '发送短信失败';
$error['verify.send_mail_failed'] = '发送邮件失败';

/**
 * captcha相关
 */
$error['captcha.invalid_code'] = '无效的验证码';

/**
 * 帐号相关
 */
$error['account.not_found'] = '账号不存在';
$error['account.locked'] = '账号被锁定，无法登录';
$error['account.too_many_login_attempts'] = '登录失败次数过多，已暂停登录，请稍后再试！';
$error['account.invalid_login_name'] = '无效的登录名';
$error['account.invalid_email'] = '无效的电子邮箱';
$error['account.invalid_phone'] = '无效的手机号';
$error['account.invalid_pwd'] = '无效的密码（字母|数字|特殊字符6-16位）';
$error['account.email_taken'] = '邮箱被占用';
$error['account.phone_taken'] = '手机号被占用';
$error['account.pwd_not_match'] = '密码不匹配';
$error['account.origin_pwd_incorrect'] = '原有密码不正确';
$error['account.login_pwd_incorrect'] = '登录密码不正确';
$error['account.register_disabled'] = '注册已关闭';
$error['account.register_with_phone_disabled'] = '手机注册已关闭';
$error['account.register_with_email_disabled'] = '邮箱注册已关闭';

/**
 * 用户相关
 */
$error['user.not_found'] = '用户不存在';
$error['user.name_taken'] = '昵称被占用';
$error['user.title_too_long'] = '头衔过长（超过30个字符）';
$error['user.about_too_long'] = '简介过长（超过255个字符）';
$error['user.profile_too_long'] = '资料过长（超过10000个字符）';
$error['user.invalid_name'] = '无效的昵称（汉字|字母|数字，2-15个字符）';
$error['user.invalid_gender'] = '无效的性别类型';
$error['user.invalid_area'] = '无效的省市地区';
$error['user.invalid_avatar'] = '无效的头像';
$error['user.invalid_edu_role'] = '无效的教学角色';
$error['user.invalid_admin_role'] = '无效的后台角色';
$error['user.invalid_vip_status'] = '无效的会员状态';
$error['user.invalid_vip_expiry_time'] = '无效的会员期限';
$error['user.invalid_lock_status'] = '无效的锁定状态';
$error['user.invalid_lock_expiry_time'] = '无效的锁定期限';

/**
 * 用户导入
 */
$error['user_import.too_many_rows'] = '导入数据过多（超过5000行）';
$error['user_import.exceed_licensed_user_count'] = '超出授权用户数量';

/**
 * 分类相关
 */
$error['category.not_found'] = '分类不存在';
$error['category.parent_not_found'] = '父级分类不存在';
$error['category.invalid_type'] = '无效的分类类型';
$error['category.invalid_icon'] = '无效的分类图标';
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
$error['course.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['course.detail_too_long'] = '详情太长（多于5000个字符）';
$error['course.invalid_model'] = '无效的模型类别';
$error['course.invalid_level'] = '无效的难度级别';
$error['course.invalid_cover'] = '无效的封面';
$error['course.invalid_user_count'] = '无效的订阅数量（范围：0-999999）';
$error['course.invalid_market_price'] = '无效的市场价格（范围：0-999999）';
$error['course.invalid_vip_price'] = '无效的会员价格（范围：0-999999）';
$error['course.invalid_study_expiry'] = '无效的学习期限';
$error['course.invalid_refund_expiry'] = '无效的退款期限';
$error['course.invalid_feature_status'] = '无效的推荐状态';
$error['course.invalid_publish_status'] = '无效的发布状态';
$error['course.content_not_ready'] = '课程内容未就绪';
$error['course.duplicate'] = '可能存在重复课程';

/**
 * 会员相关
 */
$error['vip.not_found'] = '会员不存在';
$error['vip.title_too_short'] = '标题太短（少于5个字符）';
$error['vip.title_too_long'] = '标题太长（多于30个字符）';
$error['vip.invalid_price'] = '无效的价格（范围：1-999999）';
$error['vip.invalid_expiry'] = '无效的期限（范围：1~60）';

/**
 * 课程成员
 */
$error['course_user.not_found'] = '课程学员关系不存在';
$error['course_user.invalid_expiry_time'] = '无效的过期时间';
$error['course_user.progress_too_low'] = '学习进度太低（少于30%）';
$error['course_user.has_reviewed'] = '已经评价过该课程';

/**
 * 章节相关
 */
$error['chapter.not_found'] = '章节不存在';
$error['chapter.parent_not_found'] = '父级章节不存在';
$error['chapter.invalid_model'] = '无效的课目类型';
$error['chapter.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['chapter.invalid_free_status'] = '无效的免费状态';
$error['chapter.invalid_publish_status'] = '无效的发布状态';
$error['chapter.title_too_short'] = '标题太短（少于2个字符）';
$error['chapter.title_too_long'] = '标题太长（多于30个字符）';
$error['chapter.summary_too_long'] = '简介太长（多于255个字符）';
$error['chapter.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['chapter.vod_not_ready'] = '点播资源尚未就绪';
$error['chapter.read_not_ready'] = '文章内容尚未就绪';
$error['chapter.child_existed'] = '不允许相关操作（存在子章节）';

/**
 * 点播相关
 */
$error['chapter_vod.not_found'] = '点播资源不存在';
$error['chapter_vod.invalid_trans_mode'] = '无效的转码模式';
$error['chapter_vod.invalid_file_id'] = '无效的文件编号';

/**
 * 图文相关
 */
$error['chapter_read.not_found'] = '文章不存在';
$error['chapter_read.content_too_short'] = '文章内容太短（少于10个字符）';
$error['chapter_read.content_too_long'] = '文章内容太长（多于60000个字符）';

/**
 * 评价相关
 */
$error['review.not_found'] = '评价不存在';
$error['review.invalid_rating'] = '无效的评分（范围：1-5）';
$error['review.invalid_anonymous_status'] = '无效的匿名状态';
$error['review.invalid_publish_status'] = '无效的发布状态';
$error['review.content_too_short'] = '评价内容太短（少于10个字符）';
$error['review.content_too_long'] = '评价内容太长（多于255个字符）';
$error['review.edit_not_allowed'] = '当前不允许修改操作';
$error['review.has_liked'] = '你已经点过赞啦';

/**
 * 单页相关
 */
$error['page.not_found'] = '单页不存在';
$error['page.alias_taken'] = '别名已被占用';
$error['page.title_too_short'] = '标题太短（少于2个字符）';
$error['page.title_too_long'] = '标题太长（多于50个字符）';
$error['page.alias_too_short'] = '别名太短（少于2个字符）';
$error['page.alias_too_long'] = '别名太长（多于50个字符）';
$error['page.summary_too_long'] = '摘要太长（多于255个字符）';
$error['page.content_too_short'] = '内容太短（少于10个字符）';
$error['page.content_too_long'] = '内容太长（多于10000个字符）';
$error['page.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['page.invalid_alias'] = '无效的别名（推荐使用英文作为别名）';
$error['page.invalid_publish_status'] = '无效的发布状态';

/**
 * 轮播相关
 */
$error['slide.not_found'] = '轮播不存在';
$error['slide.invalid_platform'] = '无效的平台类型';
$error['slide.invalid_target_type'] = '无效的目标类型';
$error['slide.invalid_link'] = '无效的链接地址';
$error['slide.invalid_priority'] = '无效的排序数值（范围：1-255）';
$error['slide.invalid_cover'] = '无效的封面';
$error['slide.invalid_publish_status'] = '无效的发布状态';
$error['slide.title_too_short'] = '标题太短（少于2个字符）';
$error['slide.title_too_long'] = '标题太长（多于50个字符）';
$error['slide.summary_too_long'] = '简介太长（多于255个字符）';

/**
 * 订单相关
 */
$error['order.not_found'] = '订单不存在';
$error['order.invalid_item_type'] = '无效的商品类型';
$error['order.invalid_amount'] = '无效的支付金额（范围：0.01-10万元）';
$error['order.invalid_status'] = '无效的状态类型';
$error['order.is_delivering'] = '已经下过单了，正在准备发货中';
$error['order.has_bought_course'] = '已经购买过该课程';
$error['order.pay_not_allowed'] = '当前不允许支付订单';
$error['order.cancel_not_allowed'] = '当前不允许取消订单';
$error['order.refund_not_allowed'] = '当前不允许申请退款';
$error['order.refund_not_supported'] = '该品类不支持退款';
$error['order.refund_request_existed'] = '退款申请已经存在';

/**
 * 交易相关
 */
$error['trade.not_found'] = '交易不存在';
$error['trade.create_failed'] = '创建交易失败';
$error['trade.invalid_channel'] = '无效的平台类型';
$error['trade.invalid_status'] = '无效的状态类型';
$error['trade.close_not_allowed'] = '当前不允许关闭交易';
$error['trade.refund_not_allowed'] = '当前不允许交易退款';
$error['trade.refund_request_existed'] = '退款申请已经存在，请等待处理结果';

/**
 * 退款相关
 */
$error['refund.not_found'] = '退款不存在';
$error['refund.apply_note_too_short'] = '退款原因太短（少于2个字符）';
$error['refund.apply_note_too_long'] = '退款原因太长（多于255个字符）';
$error['refund.review_note_too_short'] = '审核备注太短（少于2个字符）';
$error['refund.review_note_too_long'] = '审核备注太长（多于255个字符）';
$error['refund.cancel_not_allowed'] = '当前不允许取消退款';
$error['refund.review_not_allowed'] = '当前不允许审核退款';
$error['refund.invalid_amount'] = '无效的退款金额';
$error['refund.invalid_status'] = '无效的状态类型';

/**
 * 角色相关
 */
$error['role.not_found'] = '角色不存在';
$error['role.name_too_short'] = '名称太短（少于2个字符）';
$error['role.name_too_long'] = '名称太长（超过30个字符）';
$error['role.summary_too_long'] = '描述太长（超过255个字符）';
$error['role.route_required'] = '角色权限不能为空';

/**
 * 用户限额
 */
$error['user_limit.reach_favorite_limit'] = '超出收藏限额';
$error['user_limit.reach_daily_order_limit'] = '超出每日订单限额';
$error['user_limit.reach_daily_like_limit'] = '超出每日点赞限额';

/**
 * 课程查询
 */
$error['course_query.invalid_model'] = '无效的模型类别';
$error['course_query.invalid_level'] = '无效的难度类别';
$error['course_query.invalid_sort'] = '无效的排序类别';

/**
 * 课时学习
 */
$error['learning.invalid_request_id'] = '无效的请求编号';
$error['learning.invalid_plan_id'] = '无效的计划编号';
$error['learning.invalid_interval_time'] = '无效的间隔时间';
$error['learning.invalid_position'] = '无效的播放位置';

return $error;
