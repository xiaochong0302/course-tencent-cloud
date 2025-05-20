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
$error['security.invalid_csrf_token'] = '无效的CSRF令牌';
$error['security.invalid_http_referer'] = '无效请求来源';

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
$error['account.login_pwd_incorrect'] = '登录密码不正确';
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
$error['user.name_taken'] = '用户名被占用';
$error['user.title_too_long'] = '头衔过长（超过30个字符）';
$error['user.about_too_long'] = '简介过长（超过255个字符）';
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
 * 标签相关
 */
$error['tag.not_found'] = '标签不存在';
$error['tag.invalid_icon'] = '无效的图标';
$error['tag.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['tag.invalid_publish_status'] = '无效的发布状态';
$error['tag.name_too_short'] = '名称太短（少于2个字符）';
$error['tag.name_too_long'] = '名称太长（多于30个字符）';

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
 * 文章相关
 */
$error['article.not_found'] = '文章不存在';
$error['article.title_too_short'] = '标题太短（少于2个字符）';
$error['article.title_too_long'] = '标题太长（多于50个字符）';
$error['article.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['article.summary_too_long'] = '摘要太长（多于255个字符）';
$error['article.content_too_short'] = '内容太短（少于10个字符）';
$error['article.content_too_long'] = '内容太长（多于30000个字符）';
$error['article.invalid_source_type'] = '无效的来源类型';
$error['article.invalid_source_url'] = '无效的来源网址';
$error['article.invalid_feature_status'] = '无效的推荐状态';
$error['article.invalid_publish_status'] = '无效的发布状态';
$error['article.invalid_close_status'] = '无效的关闭状态';
$error['article.invalid_reject_reason'] = '无效的拒绝理由';
$error['article.edit_not_allowed'] = '当前不允许编辑文章';
$error['article.delete_not_allowed'] = '当前不允许删除文章';

/**
 * 问答相关
 */
$error['question.not_found'] = '问题不存在';
$error['question.title_too_short'] = '标题太短（少于5个字符）';
$error['question.title_too_long'] = '标题太长（多于50个字符）';
$error['question.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['question.summary_too_long'] = '摘要太长（多于255个字符）';
$error['question.content_too_short'] = '内容太短（少于10个字符）';
$error['question.content_too_long'] = '内容太长（多于30000个字符）';
$error['question.invalid_publish_status'] = '无效的发布状态';
$error['question.invalid_reject_reason'] = '无效的拒绝理由';
$error['question.edit_not_allowed'] = '当前不允许编辑问题';
$error['question.delete_not_allowed'] = '当前不允许删除问题';

$error['answer.not_found'] = '回答不存在';
$error['answer.content_too_short'] = '内容太短（少于10个字符）';
$error['answer.content_too_long'] = '内容太长（多于30000个字符）';
$error['answer.invalid_reject_reason'] = '无效的拒绝理由';
$error['answer.post_not_allowed'] = '当前不允许发布回答';
$error['answer.edit_not_allowed'] = '当前不允许编辑回答';
$error['answer.delete_not_allowed'] = '当前不允许删除回答';

/**
 * 评论相关
 */
$error['comment.not_found'] = '评论不存在';
$error['comment.parent_not_found'] = '上级评论不存在';
$error['comment.invalid_item_type'] = '无效的条目类型';
$error['comment.invalid_publish_status'] = '无效的发布状态';
$error['comment.invalid_reject_reason'] = '无效的拒绝理由';
$error['comment.content_too_short'] = '内容太短（少于2个字符）';
$error['comment.content_too_long'] = '内容太长（多于1000个字符）';

/**
 * 课程相关
 */
$error['course.not_found'] = '课程不存在';
$error['course.title_too_short'] = '标题太短（少于5个字符）';
$error['course.title_too_long'] = '标题太长（多于50个字符）';
$error['course.summary_too_long'] = '标题太长（多于255个字符）';
$error['course.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['course.details_too_long'] = '详情太长（多于5000个字符）';
$error['course.invalid_model'] = '无效的模型类别';
$error['course.invalid_level'] = '无效的难度级别';
$error['course.invalid_cover'] = '无效的封面';
$error['course.invalid_user_count'] = '无效的学员数量（范围：0-999999）';
$error['course.invalid_origin_price'] = '无效的原始价格（范围：0-999999）';
$error['course.invalid_market_price'] = '无效的优惠价格（范围：0-999999）';
$error['course.invalid_vip_price'] = '无效的会员价格（范围：0-999999）';
$error['course.invalid_study_expiry'] = '无效的学习期限';
$error['course.invalid_refund_expiry'] = '无效的退款期限';
$error['course.invalid_feature_status'] = '无效的推荐状态';
$error['course.invalid_publish_status'] = '无效的发布状态';
$error['course.content_not_ready'] = '课程内容未就绪';

/**
 * 面授课程相关
 */
$error['course_offline.invalid_start_date'] = '无效的开始日期';
$error['course_offline.invalid_end_date'] = '无效的结束日期';
$error['course_offline.start_gt_end'] = '开始日期大于结束日期';
$error['course_offline.invalid_user_limit'] = '无效的用户限额（范围：1-999）';
$error['course_offline.invalid_location'] = '无效的上课地点（范围10-50字符）';

/**
 * 话题相关
 */
$error['topic.not_found'] = '话题不存在';
$error['topic.title_too_short'] = '标题太短（少于2个字符）';
$error['topic.title_too_long'] = '标题太长（多于50个字符）';
$error['topic.summary_too_long'] = '简介太长（多于255个字符）';
$error['topic.invalid_cover'] = '无效的封面';
$error['topic.invalid_publish_status'] = '无效的发布状态';

/**
 * 套餐相关
 */
$error['package.not_found'] = '套餐不存在';
$error['package.title_too_short'] = '标题太短（少于5个字符）';
$error['package.title_too_long'] = '标题太长（多于50个字符）';
$error['package.summary_too_long'] = '简介太长（多于255个字符）';
$error['package.invalid_cover'] = '无效的封面';
$error['package.invalid_market_price'] = '无效的优惠价格';
$error['package.invalid_vip_price'] = '无效的会员价格';
$error['package.invalid_publish_status'] = '无效的发布状态';

/**
 * 会员相关
 */
$error['vip.not_found'] = '会员不存在';
$error['vip.title_too_short'] = '标题太短（少于5个字符）';
$error['vip.title_too_long'] = '标题太长（多于30个字符）';
$error['package.invalid_price'] = '无效的价格（范围：0.01-10000）';
$error['package.invalid_expiry'] = '无效的期限（范围：1~60）';

/**
 * 课程成员
 */
$error['course_user.not_found'] = '课程学员关系不存在';
$error['course_user.invalid_expiry_time'] = '无效的过期时间';
$error['course_user.review_not_allowed'] = '当前不允许评价课程';
$error['course_user.has_imported'] = '已经加入过该课程';
$error['course_user.has_reviewed'] = '已经评价过该课程';

/**
 * 章节相关
 */
$error['chapter.not_found'] = '章节不存在';
$error['chapter.parent_not_found'] = '父级章节不存在';
$error['chapter.invalid_priority'] = '无效的排序值（范围：1-255）';
$error['chapter.invalid_free_status'] = '无效的免费状态';
$error['chapter.invalid_publish_status'] = '无效的发布状态';
$error['chapter.title_too_short'] = '标题太短（少于2个字符）';
$error['chapter.title_too_long'] = '标题太长（多于30个字符）';
$error['chapter.summary_too_long'] = '简介太长（多于255个字符）';
$error['chapter.vod_not_ready'] = '点播资源尚未就绪';
$error['chapter.read_not_ready'] = '文章内容尚未就绪';
$error['chapter.live_not_start'] = '直播尚未开始';
$error['chapter.live_time_empty'] = '直播时间尚未设置';
$error['chapter.offline_time_empty'] = '面授时间尚未设置';
$error['chapter.child_existed'] = '不允许相关操作（存在子章节）';

/**
 * 点播相关
 */
$error['chapter_vod.not_found'] = '点播资源不存在';
$error['chapter_vod.invalid_duration'] = '无效的视频时长';
$error['chapter_vod.invalid_file_id'] = '无效的文件编号';
$error['chapter_vod.invalid_file_url'] = '无效的文件地址';
$error['chapter_vod.invalid_file_ext'] = '无效的文件格式（目前只支持mp4，m3u8）';
$error['chapter_vod.remote_file_required'] = '请填写远程播放地址';

/**
 * 直播相关
 */
$error['chapter_live.not_found'] = '直播资源不存在';
$error['chapter_live.invalid_start_time'] = '无效的开始时间';
$error['chapter_live.invalid_end_time'] = '无效的结束时间';
$error['chapter_live.start_gt_end'] = '开始时间大于结束时间';
$error['chapter_live.time_too_long'] = '直播时间太长（超过3小时）';

/**
 * 图文相关
 */
$error['chapter_read.not_found'] = '文章不存在';
$error['chapter_read.content_too_short'] = '文章内容太短（少于10个字符）';
$error['chapter_read.content_too_long'] = '文章内容太长（多于60000个字符）';

/**
 * 面授相关
 */
$error['chapter_offline.invalid_start_time'] = '无效的开始时间';
$error['chapter_offline.invalid_end_time'] = '无效的结束时间';
$error['chapter_offline.start_gt_end'] = '开始时间大于结束时间';

/**
 * 评价相关
 */
$error['review.not_found'] = '评价不存在';
$error['review.invalid_rating'] = '无效的评分（范围：1-5）';
$error['review.invalid_publish_status'] = '无效的发布状态';
$error['review.content_too_short'] = '评价内容太短（少于10个字符）';
$error['review.content_too_long'] = '评价内容太长（多于255个字符）';
$error['review.edit_not_allowed'] = '当前不允许修改操作';
$error['review.has_liked'] = '你已经点过赞啦';

/**
 * 咨询相关
 */
$error['consult.not_found'] = '咨询不存在';
$error['consult.invalid_rating'] = '无效的评分（范围：1-5）';
$error['consult.invalid_private_status'] = '无效的私密状态';
$error['consult.invalid_publish_status'] = '无效的发布状态';
$error['consult.question_duplicated'] = '你已经咨询过类似问题啦';
$error['consult.question_too_short'] = '问题内容太短（少于5个字符）';
$error['consult.question_too_long'] = '问题内容太长（多于1000个字符）';
$error['consult.answer_too_short'] = '回复内容太短（少于5个字符）';
$error['consult.answer_too_long'] = '回复内容太长（多于1000个字符）';
$error['consult.edit_not_allowed'] = '当前不允许修改操作';
$error['consult.has_liked'] = '你已经点过赞啦';

/**
 * 单页相关
 */
$error['page.not_found'] = '单页不存在';
$error['page.title_too_short'] = '标题太短（少于2个字符）';
$error['page.title_too_long'] = '标题太长（多于50个字符）';
$error['page.alias_too_short'] = '别名太短（少于2个字符）';
$error['page.alias_too_long'] = '别名太长（多于50个字符）';
$error['page.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['page.content_too_short'] = '内容太短（少于10个字符）';
$error['page.content_too_long'] = '内容太长（多于30000个字符）';
$error['page.invalid_alias'] = '无效的别名（推荐使用英文作为别名）';
$error['page.invalid_publish_status'] = '无效的发布状态';

/**
 * 帮助相关
 */
$error['help.not_found'] = '帮助不存在';
$error['help.title_too_short'] = '标题太短（少于2个字符）';
$error['help.title_too_long'] = '标题太长（多于50个字符）';
$error['help.keyword_too_long'] = '关键字太长（多于100个字符）';
$error['help.content_too_short'] = '内容太短（少于10个字符）';
$error['help.content_too_long'] = '内容太长（多于30000个字符）';
$error['help.invalid_priority'] = '无效的排序数值（范围：1-255）';
$error['help.invalid_publish_status'] = '无效的发布状态';

/**
 * 轮播相关
 */
$error['slide.not_found'] = '轮播不存在';
$error['slide.invalid_platform'] = '无效的平台类型';
$error['slide.invalid_target'] = '无效的目标类型';
$error['slide.invalid_link'] = '无效的链接地址';
$error['slide.invalid_priority'] = '无效的排序数值（范围：1-255）';
$error['slide.invalid_cover'] = '无效的封面';
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
$error['order.invalid_amount'] = '无效的支付金额';
$error['order.invalid_status'] = '无效的状态类型';
$error['order.is_delivering'] = '已经下过单了，正在准备发货中';
$error['order.has_bought_course'] = '已经购买过该课程';
$error['order.has_bought_package'] = '已经购买过该套餐';
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
$error['role.routes_required'] = '角色权限不能为空';

/**
 * 用户限额
 */
$error['user_limit.reach_favorite_limit'] = '超出收藏限额';
$error['user_limit.reach_daily_report_limit'] = '超出每日举报限额';
$error['user_limit.reach_daily_article_limit'] = '超出每日文章限额';
$error['user_limit.reach_daily_question_limit'] = '超出每日提问限额';
$error['user_limit.reach_daily_answer_limit'] = '超出每日回答限额';
$error['user_limit.reach_daily_comment_limit'] = '超出每日评论限额';
$error['user_limit.reach_daily_consult_limit'] = '超出每日咨询限额';
$error['user_limit.reach_daily_order_limit'] = '超出每日订单限额';
$error['user_limit.reach_daily_like_limit'] = '超出每日点赞限额';

/**
 * 文章查询
 */
$error['article_query.invalid_category'] = '无效的分类类别';
$error['article_query.invalid_tag'] = '无效的标签类别';
$error['article_query.invalid_sort'] = '无效的排序类别';

/**
 * 课程查询
 */
$error['course_query.invalid_top_category'] = '无效的方向类别';
$error['course_query.invalid_sub_category'] = '无效的分类类别';
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

/**
 * 联系信息相关
 */
$error['user_contact.invalid_name'] = '无效的用户姓名';
$error['user_contact.invalid_phone'] = '无效的手机号码';
$error['user_contact.invalid_add_province'] = '无效的地址（省）';
$error['user_contact.invalid_add_city'] = '无效的地址（市）';
$error['user_contact.invalid_add_county'] = '无效的地址（区）';
$error['user_contact.invalid_add_other'] = '无效的地址（详）';

/**
 * 积分兑换相关
 */
$error['point_gift.not_found'] = '礼品不存在';
$error['point_gift.name_too_short'] = '礼品名称太短（少于2字符）';
$error['point_gift.name_too_long'] = '礼品名称太长（超过30字符）';
$error['point_gift.details_too_long'] = '礼品详情太长（多于30000个字符）';
$error['point_gift.invalid_cover'] = '无效的封面';
$error['point_gift.invalid_type'] = '无效的类型';
$error['point_gift.invalid_point'] = '无效的积分值（范围：1-999999）';
$error['point_gift.invalid_stock'] = '无效的库存值（范围：1-999999）';
$error['point_gift.invalid_redeem_limit'] = '无效的兑换限额（范围：1-10）';
$error['point_gift.invalid_publish_status'] = '无效的发布状态';

$error['point_gift_redeem.not_found'] = '兑换不存在';
$error['point_gift_redeem.course_not_published'] = '课程尚未发布';
$error['point_gift_redeem.course_free'] = '课程当前免费，无需积分兑换';
$error['point_gift_redeem.course_owned'] = '您已经拥有课程，无需积分兑换';
$error['point_gift_redeem.no_user_contact'] = '您尚未设置收货地址，请前往用户中心设置';
$error['point_gift_redeem.reach_redeem_limit'] = '超出物品兑换限额';
$error['point_gift_redeem.no_enough_point'] = '您的积分余额不足以抵扣此次兑换';
$error['point_gift_redeem.no_enough_stock'] = '兑换物品库存不足';

/**
 * 举报相关
 */
$error['report.not_found'] = '举报不存在';
$error['report.has_reported'] = '你已经举报过啦';
$error['report.reason_required'] = '请选择举报理由';
$error['report.remark_required'] = '请填写补充说明';

return $error;
