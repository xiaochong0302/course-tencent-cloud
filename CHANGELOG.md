### [v1.7.8](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.8)(2025-06-20)

- 移除ThrottleLimit
- 增加CloseLiveTask
- 增加搜索页图片alt属性striptags过滤
- 后台增加返回顶部快捷方式
- 前台fixbar增加联系电话
- 优化安装脚本
- 优化课时列表直播提示
- 优化后台返回链接
- 优化统计分析代码位置
- 直播回调后更新课时缓存
- 后台清空头像->上传头像
- sitemap.xml直接写入网站根目录

### [v1.7.7](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.7)(2025-04-20)

- 优化索引管理工具
- 优化章节等页面UI
- 修正workerman中onMessage问题
- 修正非免费课程试听问题
- 优化layer窗口中的表单跳转
- 文件清理以及命名优化
- 优化倒计时

### [v1.7.6](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.6)(2025-03-22)

- 升级layui-v2.9.25
- 去除发货中不必要的异常抛出
- 去除文章和问题缓存重建
- 去除多余的文件引用
- 修正每日访问站点积分问题
- 限制全文搜索关键字长度
- 统一规划二维码样式

### [v1.7.5](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.5)(2025-02-22)

- 优化后台统计图表
- 优化图片放大查看
- 优化错误处理机制
- 优化前台编辑器页面
- 去除一些过度的设计
- 精简属性空判断
- 规整redirect
- 优化bootstrap
- 优化logger
- 优化contact
- 优化logo
- 优化nav

### [v1.7.4](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.4)(2024-12-10)

- 更新layui-v2.9.20
- 优化编辑器内容自动提交
- 修正课时详情页目录高亮问题
- 修正CommentInfo中点赞判断
- 精简AccountSearchTrait
- 优化kg_h5_index_url()
- 优化CourseUserTrait
- 优化kg_setting()
- 优化CsrfToken

### [v1.7.3](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.3)(2024-10-10)

- 更新layui-v2.9.16
- 增加编辑器内容自动提交
- 修改文章和提问可用tag数量
- 优化findUserActiveSessions
- 优化findUserActiveTokens
- 优化上传文件失败抛出异常
- 优化默认文件上传
- 优化用户锁定相关

### [v1.7.2](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.2)(2024-07-31)

- 更新layui-v2.9.14
- 优化docker自动化脚本
- 修正教师直播通知
- 修正课程分类删选问题
- 后台增加客户服务入口
- redis增加expire方法
- 日志记录增加log.trace参数
- 精简代码

### [v1.7.1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.1)(2024-06-31)

- 更新layui-v2.9.10
- 更新docker国内镜像地址
- 增加导入镜像构建容器的方式
- 调整微信公众号模板消息
- 移除加载富文本编辑器初始化的语言文件
- 移除consult中多余的chapter_id属性
- 修正课程列表顶部过滤条件区块不能收缩问题
- 用户中心第三方登录列表增加过滤条件
- 后台增加打开/关闭左侧菜单提示
- 优化整理文件mimeType
- iconfont资源本地化
- 优化UploadController
- 优化富文本内容显示样式
- 简化内容图片放大监听
- 去除课程打赏相关内容
- 课程增加能否发布检查

### [v1.7.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.7.0)(2024-05-15)

- 升级layui-2.9.8
- 调整html编辑器属性
- 增加代码块内容复制
- 清理无用的Captcha配置
- 联系人QQ改为上传二维码图片
- 修正logo,favicon上传路径
- 登录后台同时登录前台
- 移动端修正评论发表

### [v1.6.9](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.9)(2024-04-15)

- 增加用户删除和还原功能
- 增加unauthorized响应
- 增加post方式传递csrf_token
- 删除chapter中resource_count,consult_count属性
- 精简csrf_token白名单
- 拆解优化migrations创建表脚本
- 修正chapter_user时长重复计数问题
- 修正后台刷新首页缓存问题
- 修正home模块中编辑器图片上传
- 优化文章和提问搜索条件
- 优化课程详情页排版
- 优化storage上传
- 优化CategoryTreeList
- 优化CourseUserTrait
- 更新layui-v2.9.7

### [v1.6.8](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.8)(2024-01-30)

- 修正course_user中active_time未更新问题
- 修正主页simple模式免费课程模块样式问题
- 修正chapter_user中plan_id=0问题
- 修正课时评论管理链接
- 修正用户active_time搜索条件
- 修正课时发布switch开关
- 精简chapter/lessons.volt
- 去除league/commonmark包
- 去除分类等必选判断
- 更新layui-v2.9.3
- 使用ServiceTrait精简代码
- 优化AccountTrait
- 优化错误处理

### [v1.6.7](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.7)(2023-12-15)

- 增加文章分类功能
- 增加问题分类功能
- 增加审核等批量功能
- 增加若干业务插件埋点
- 精简重构大量业务逻辑
- 移除秒杀营销功能
- 已发现的问题修复

### [v1.6.6](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.6)(2023-08-30)

- 还原意外删除的AnswerList.php文件
- 修正邮箱注册提交按钮不可用问题
- 去除删除远程课件逻辑
- 增加课程课件资料总览
- 优化cleanDemoDataTask脚本
- 优化tag表migration脚本
- 命名结构等常规优化

### [v1.6.5](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.5)(2023-07-15)

- 升级layui-v2.8.8
- 使用本地图像验证码
- 优化计划任务脚本
- 优化日志清理脚本
- 优化钉钉webhook
- 修正图文分享参数问题

### [v1.6.4](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.4)(2023-06-15)

- 增加推荐课程等Widget
- 更新Composer包
- 修正验证空口令问题
- 优化订单确认页样式
- 优化课程等Me相关信息
- 优化分享URL
- 优化用户课程查找
- 优化通知相关
- 优化Providers
- 优化课程章节权限
- 优化钉钉机器人

### [v1.6.3](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.3)(2023-05-08)

- 强化文章|提问|课程列表参数检查
- 优化HtmlPurifier内容过滤
- 优化排序条件和分页重复问题
- 优化课程搜索分组条件样式
- 优化课程学习时长同步
- 优化程序语法层面
- 更新Layui-v2.8.2
- 替换ip2region包
- 去除未支付“新鲜”订单检查
- 修正手续费率设置为0无效问题

### [v1.6.2](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.2)(2023-02-12)

- 增加ServerMonitor监控指标配置
- 同步更新腾讯云短信内容规则
- 文章和问答增加评论开关属性
- 修正视频记忆播放无效问题
- 升级composer包版本
- 优化Repo查询默认排序
- 优化管理后台细节
- 优化二维码输出
- 优化评分检查

### [v1.6.1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.1)(2022-12-12)

- 富文本编辑器增加粘贴图片和远程图片本地化
- 修正用户通知标记为已读，计数不归零问题
- 修正播放器中央按钮显示问题
- 优化腾讯云播放地址鉴权参数
- 优化热门作者，答主和问题
- 优化学员学习记录显示
- 优化表单数据提交体验
- 优化单章节层级显示
- 优化ServerInfo类

### [v1.6.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.6.0)(2022-10-26)

- 播放器中间增加大号播放按钮
- 单页和帮助增加浏览计数属性
- logo上增加首页链接
- 修正分类默认图标问题
- 修正layui-main样式更新带来的问题
- 更新composer包
- 调整退款手续费范围
- 导航部分，教师->师资
- 优化分页组件参数
- 优化内容表格样式
- 优化热门问题和热门答主
- 优化通知计数方式

### [v1.5.9](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.9)(2022-09-20)

- 修正内容图片上传问题
- 去除user全文索引
- 调整notice目录结构
- 更新默认图片
- 更新直播名格式化
- 更新微博分享链接
- 文章单页等增加SEO关键字
- 专题增加封面上传
- 优化router扫描规则
- 升级layui至v2.7.6
- 增加用户协议和隐私政策
- 优化错误日志

### [v1.5.8](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.8)(2022-08-28)

- 整理migrations
- 更新自动安装脚本
- 优化登录/注册/忘记密码页
- 修复移动端首页课程缓存刷新
- sitemap条目增加过滤条件

### [v1.5.7](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.7)(2022-08-18)

- 清理群组残留
- 升级腾讯云存储SDK到v2.5.6
- GuzzleHttp升级到v6.5.7
- 优化HtmlPurifier缓存目录自动创建
- 优化问题回答排序问题
- 优化腾讯云短信错误日志
- 整理查询构建语句
- 整理优化CSS

### [v1.5.6](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.6)(2022-08-08)

- 增加应用内命令行migrations
- 移除群组和微聊模块
- kindeditor替换vditor
- markdown转html

### [v1.5.5](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.5)(2022-07-27)

- 修正获分类查询条件
- 修正锁定账户还能登录的问题
- 发货增加noMatchedHandler
- 增加demo数据清理脚本
- 用户课程列表增加角色限定条件
- 精简模块加载和路由扫描
- 优化CsrfToken
- 去除无实质作用的数据表优化

### [v1.5.4](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.4)(2022-06-15)

- 增加migration助手SettingTrait
- 增加积分兑换会员
- 增加ISP备案和电子执照配置
- 增加获取视频时长补偿机制
- 优化课程和套餐发货
- 优化验证码
- 优化视频点播回调处理任务
- 优化章节排序初始值和步长
- 优化后台视频上传和转码
- 修正获取子分类查询条件

### [v1.5.3](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.3)(2022-05-30)

- 优化章节排序初始值和步长
- 修复删除群组前台列表仍然显示问题
- 设置360浏览器的默认模式为webkit
- 修复首页简单模式课程项顶部缺少空白
- vditor本地化，彻底弃用cdn.jsdelivr.net
- 调整markdown样式

### [v1.5.2](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.2)(2022-04-17)

- 补充话题列表课程数据结构
- 调整发送验证码相关样式
- 优化套餐和话题下拉课程数据显示
- 去除礼物详情中多出来的"}}"标签
- 修正关闭秒杀订单时没有回填库存的问题
- vditor编辑器切换为七牛cdn加速

### [v1.5.1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.1)(2022-03-17)

- 推荐课程等列表补充属性
- 修正后台评价列表中的搜索链接
- 修正后台点播设置视频码率后500错误问题
- 修正多码率远程播放地址部分为空播放问题
- 修正更新套餐课程缓存传参数据类型问题
- 修正第三方登录解除绑定失败问题
- 使用ServiceTrait归纳获取服务代码
- 优化anonymous隐藏部分字符函数
- 调整积分兑换相关定义命名
- 去除js_vars中关于IM客服的配置
- 增加验证码开关

### [v1.5.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.5.0)(2022-02-17)

- 调整对内部人员通知任务类型的前缀
- 调整微信和短信通知发送判断逻辑
- 清理后台实用工具的无用文件内容
- 支付后解除秒杀商品锁定
- 加强支付流程数据验证
- 加强退款流程数据验证
- 优化账户创建数据流
- 优化课程创建数据流
- 优化章节创建数据流
- 优化积分商品兑换
- 优化发货逻辑

### [v1.4.9](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.9)(2022-01-01)

- 修正订单消费未奖励积分问题
- 修正前台课程分类排序无效问题
- 修正后台点播防盗链配置显隐藏状态问题
- 修正分享链接非h5环境也会跳转到h5问题
- 修正后台钉钉配置调用错误
- 使用腾讯云新SDK发送短信
- 优化show400错误输出页
- 优化下单时产品检查逻辑
- 优化上传文件筛选限制
- 优化后台配置更新

### [v1.4.8](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.8)(2021-11-28)

- 修正后台下载课程附件问题
- 修正后台登录检查跳转地址
- 修正公众号关注二维码样式问题
- 优化发货失败自动退款逻辑
- 创建交易时增加订单支付检查
- H5增加底部tab图标

### [v1.4.7](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.7)(2021-10-28)

- 更新README.md
- 优化分页查询参数过滤
- 优化后台学员添加和搜索
- 优化后台学员课程过期管理
- 增加编辑会员特权功能
- 增加清空用户头像功能
- 增加编辑器内站外图片自动保存到本地
- 增加CSRF放行白名单
- 完善订单|交易|退款序号

### [v1.4.6](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.6)(2021-10-18)

- 完善首页文章缓存的获取条件
- 完善热门专题的获取条件
- 优化课程章节列表逻辑
- 更新教学中心我的课程获取逻辑
- 修正后台点播和面授类型课时列表宽度未100%铺满问题
- 完善添加积分礼品的逻辑
- 修正编辑课程类型礼品时编辑器初始化js报错
- 修正非root用户后台添加用户时报错
- 修正微信等第三方登录code被重用问题
- 手机端访问web端地址自动跳转到手机端
- 增加锁定用户逻辑(会自动登出锁定用户)
- 增加虚假课程订阅数(用于营销效果)

### [v1.4.5](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.5)(2021-09-27)

- 修正点击内容分享到微信会额外出现公众号二维码的问题
- 修正后台首页提问和回答的数量统计
- 调整登录限制（邮箱｜手机）为注册限制
- 调整订单发货为每一分钟执行一次
- 增强课时安全性，无权限时不返回播放地址或内容
- 抽离出文章关闭，仅我可见操作
- 增加退出群组和解除好友接口
- 增加删除文章和提问接口
- 增加首页推荐教师接口
- 增加微信公众号支付处理
- 增加取消订单功能
- 优化订单API结构
- 优化计划任务

### [v1.4.4](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.4)(2021-09-17)

- 后台增加邮件手机登录选择配置
- 增加移动端支付选项配置
- 首页增加秒杀，直播，提问，文章接口
- 增加秒杀列表列表接口
- 调整markdown解析安全级别
- 精简取消点赞以及取消收藏逻辑
- 修复浮点转整型精度丢失造成的支付回调失败
- 修复竖屏直播时造成的位置错乱
- 修复视频清晰度配置序列化问题
- 修复评论取消点赞数量不变问题
- 修复章节资源数量问题
- 修复删除课程后引发的用户课程列表错误问题
- 修正课程咨询列表查询条件
- 修正回答，兑换礼品说明重复转译的问题
- 资源下载查询主键由md5改为加密的ID
- 去除上传文件md5唯一索引
- 去除课程发布对章节的要求
- 去除点播回调中的处理数量限制
- 优化文章，课程，提问，群组全文搜索
- 优化直播列表数据结构
- 优化章节目录交互呈现
- 优化后台添加学员重复检查
- 优化订单发货逻辑
- 优化公众号订阅逻辑

### [v1.4.3](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.3)(2021-08-23)

- 优化邮件验证码
- 优化logo和favicon上传
- 优化api验证码中使用的ticket和rand
- 优化点播和直播地址获取
- 修复部分清晰度外链播放地址为空时切换卡死问题
- 增加QQ，微信，微博，邮件，电话等联系配置
- 用户控制台文章和提问列表增加删除过滤
- 去除layim在线客服
- 提高视频转码分辨率

### [v1.4.2](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.2)(2021-08-13)

- 后台增加转码码率配置选项
- 后台增加微聊配置开关
- 优化外链播放地址逻辑
- 访问课程文章等未发布资源404处理
- 优化课件上传不返回md5值的处理
- 优化用户中心内容数据展示
- 前台暂时屏蔽文章仅我可见和关闭评论功能
- 直播增加极速码率选项
- 调整码率标签对应
- 修复后台数据统计中心Hash缓存问题
- 修复未发布的课程仍然可购买问题
- 修复购买课程后学员人数未增加问题
- 增加同步课程数据统计脚本

### [v1.4.1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.1)(2021-08-08)

- AnswerInfo结构补充遗漏的comment_count字段
- AnswerList结构去除deleted字段
- ChapterList结构补充published字段
- 使用开源的播放器DPlayer替换腾讯TcPlayer
- 修正第三方登录开关的判断
- 修正课程方向过滤问题
- 修正教师主页会显示未发布课程问题
- 修正评论删除点击无效问题
- 优化课时列表可点击权限判断
- 优化来源检查域名带端口问题
- 优化微信公众号业务处理类

### [v1.4.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.4.0)(2021-08-03)

### 更新

- 单页增加自定义别名访问
- 回答增加评论功能
- 顶部导航微聊增加开关控制
- 更新默认的ICP备案链接指向
- 更正部分model定义中字段的类型申明
- 优化章节过多导致页面过长问题
- 优化评论前端部分相关逻辑和交互
- 优化403错误页面，使用forward代替redirect
- 优化播放地址中带queryString的扩展名检查
- 修正解除第三登录绑定500错误问题
- 修正教师教授课程未过滤已删除课程问题
- 修正咨询编辑500错误问题
- 修正后台列表中restore_url未定义问题

### [v1.3.9](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.9)(2021-07-24)

### 更新

- 修正分类下无课程时会查询出所有课程问题
- 修正sitemap中的部分路径问题
- 修正课程套餐相关问题
- 优化问答部分相关逻辑
- 优化评论部分相关逻辑
- 优化浏览器Title显示
- 优化审核和举报相关逻辑
- 优化命令行脚本执行输出
- 优化API的分页返回结构
- 增加文章，问答，评论相关API  
- 增加重新统计tag中相关计数计划任务
- 增加tag的使用范围，文章，问题，课程计数
- 站点logo和favicon使用随机文件名
- 增加评价，咨询审核
- 去除编辑器中的酷瓜云课堂标识
- 清理数据迁移文件

### [v1.3.8](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.8)(2021-07-11)

### 更新

- 更正readme中github仓库信息
- 增加清除点播地址缓存命令
- 若干缓存键名重命名 后台站点名称修改为用户站点名称
- 标签名称比较忽略大小写
- 重新设计前后台登录界面
- 更正后台存储设置中图片样式的参数描述
- 记录逻辑删除后浏览重定向到404
- 修正图文类型的章节markdown解析问题
- 优化文章和提问不必要的标签数据提交
- 图文中图片增加点击放大预览功能
- 各数据结构中增加若干业务字段
- COS存储中去除多余的年月目录结构
- 清理优化css
- 修正直播地址问题
- 修正评论审核路由问题
- 修正取消收藏问题

### [v1.3.7](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.7)(2021-06-14)

### 更新

- 升级layui到v2.6.8
- 升级腾讯云播放器到v2.4.0
- 点播增加外链支持
- 源文件增加版权信息
- 优化模块继承基类
- 优化评论审核机制
- 优化课程和群组状态协同逻辑
- 优化用户索引重建逻辑

### [v1.3.6](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.6)(2021-06-04)

### 更新

- 清理没有用到的引用
- 优化界面和CSS样式
- 优化视频无法获取时长处理逻辑
- 优化视频无法转码处理逻辑
- 优化评论审核机制
- 优化评论相关数据更新逻辑
- 优化文章，问答，评论数据更新
- 优化内容标签的更新逻辑
- 优化首页H5的跳转判断
- 优化单页的浏览权限
- 优化Model中的事件方法
- 优化kg_parse_summary函数
- 用户主页加入问答列表
- 修复无法关闭问题讨论
- 修复编辑群组的路由
- 直播去除FLV方式拉流
- xs.question.ini加入忽略列表
- kg_user表增加comment_count字段

### [v1.3.5](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.5)(2021-05-20)

### 更新

- 更新演示数据
- 优化安装脚本install.sh
- 升级脚本upgrade.sh中加入更新导航缓存
- 撰写文章和提问markdown编辑器通栏显示
- 完善文章和问题的浏览权限
- 优化通用ajax表单提交
- 文章，提问，回答点赞作者有提醒和积分奖励
- 前台增加针对回答的预览访问地址
- 前台增加文章，问题，回答，评论加入举报功能
- 后台增加文章，问题，回答，评论的举报审核功能
- 后台首页增加审核队列统计

### [v1.3.4](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.4)(2021-05-13)

### 更新

- 增加问答功能
- 增加标签关注功能
- 优化标签功能
- 优化文章功能以及全文搜索
- 优化课程评价，咨询，文章等相关统计
- 优化前台界面
- 后台增加提问和回答审核功能
- 后台增加查看用户在线记录
- 修正后台编辑角色权限错误

### [v1.3.3](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.3)(2021-04-30)

### 更新

- 前台增加文章发布功能
- 增加文章，咨询，评价，评论相关事件站内提醒
- 增加文章，咨询，评价，评论事件埋点
- 后台首页增加若干统计项目
- 后台增加文章审核功能
- 重构积分历史记录
- 优化在线统计方式
- 优化前台界面

### [v1.3.2](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.2)(2021-04-20)

### 更新

- 前台增文章和章节评论功能
- 后台增加评论相关管理功能
- 优化课程，章节，文章等前台界面
- 优化分享链接的生成和跳转方式
- 优化课程，章节，文章相关js
- 优化后台数据展示
- 修正后台分类二级分类错位问题
- 修正文章命名空间问题
- 修正后台轮播没有保存问题

### [v1.3.1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.1)(2021-04-09)

### 更新

- 后台增加文章功能
- 后台增加标签功能
- 增加文章全文检索
- 整理命名空间别名
- 更新部分链接打开方式
- xm-select搜索忽略大小写
- 补充遗漏的面授模型章节相关迁移文件
- 修正上次字段整理导致的字段不存在问题
- 修正上次整理发布字段导致的添加单页和帮助错误
- 增加开启/关闭站点终端命令

### [v1.3.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.3.0)(2021-03-26)

### 更新

- 课程增加面授模型
- 重构前台群组成员管理
- 后台增加群组成员管理
- 重构订单存储商品详情数据结构
- 调整用户和群组列表等UI

### [v1.2.9](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.9)(2021-03-22)

### 更新

- 增加限时秒杀功能
- 更新phinx默认环境配置项
- 优化存储相关命名以及逻辑
- 重构轮播图表结构
- 重构套餐数表结构
- 重构会员表结构
- 重构xm-select插件选取内容方式
- 整理UI展现形式

### [v1.2.8](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.8)(2021-03-08)

### 更新

- 数据库迁移脚本整理
- 数据表软删除字段整理
- 微信公众号路由整理
- 退款增加手续费逻辑
- 课程增加不支持退款逻辑
- 会员价格和期限可通过后台配置
- 修复IM通知中字段重命名导致的问题
- 修复购买会员会员标识未改变的问题
- 会员中心订单列表样式调整

### [v1.2.7](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.7)(2021-02-26)

### 新增

- 钉钉机器人群消息通知
- demo分支重置演示帐号计划任务
- 添加学员自动加入相关课程群组
- 后台查看积分记录

### 更新

- 路由重命名 admin.group -> admin.im_group
- 路由重命名 home.group -> home.im_group
- 样式重命名 sidebar-teacher-card -> sidebar-user-card
- 去除顶部积分导航
- 用户中心部分样式调整
- 后台部分导航调整
- 不能删除课程教师问题
- 积分模块可通过后台控制是否启用
- 解除好友关系后好友数量未递减

### [v1.2.6](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.6)(2021-02-20)

### 新增

- 积分兑换机制
- 课程增加原价属性
- gitee提交webhooks自动化部署脚本

### 更新

- course和chapter数据迁移文件中遗漏了recourse_count字段
- app/Caches/TopicCourseList不存在
- Model文件属性定义默认值
- 隐藏非付费课程的咨询服务
- 教学中心教师直播推流按钮无反应
- 用户中心部分样式调整
- 播放器清晰度标签和实际的清晰度不对应
- CNZZ统计代码会显示出站长统计图标
- 自动安装后访问站点500错误
- 自动更新脚本可更新css和js版本号

### [v1.2.5](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.5)(2021-01-20)

### 新增

- 自动化安装脚本
- 自动化更新脚本
- 自动化备份脚本

### 更新

- 更新ip2region包
- 更新php-cron-scheduler包
- 替换aferrandini/phpqrcode为endroid/qr-code
- 替换joyqi/hyper-down为league/commonmark
- 移除lcobucci/jwt包
- 相关连接指向官网

### [v1.2.4](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.4)(2021-01-10)

#### 增加

- 后台增加上传logo和favicon图标
- 后台增加公众号自定义菜单配置
- 课程页增加咨询 

### 优化

- oauth中state参数为安全base64加解码
- 判断是否api请求逻辑
- findById参数类型不对时抛出异常
- task表增加索引加快数据查找
- markdown内容解析改由后端完成
- 公众号应答处理逻辑

### [v1.2.3](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.3)(2021-01-03)

#### 增加

- 多人使用同一帐号防范机制
- 首页缓存刷新工具
- 课程综合评分
- 课程推荐

#### 修复

- phinx-migration-generator 无符号问题
- online表并发写入重复记录问题 
- 计划任务生成sitemap.xml失败

### [v1.2.2](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.2)(2020-12-24)

#### 增加

- 登录账户微信提醒
- 购买成功微信提醒
- 退款成功微信提醒
- 开始直播微信提醒
- 咨询回复微信提醒
- 咨询回复短信提醒

#### 修复

- 创建章节，关联表数据没有生成
- 创建群组，没有生成max_im_group_id缓存
- 课程分类列表没有过滤掉帮助分类的内容
- 创建角色字段routes MySQL text 类型报错
- 低品质视频无法播放
- 后台遗漏的权限

### [v1.2.1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.1)(2020-12-10)
- 增加QQ，微信，微博第三方登录
- 代码优化以及问题修复

### [v1.2.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.0)(2020-11-25)
- 增加客户端api
- 代码优化以及问题修复

### [v1.1.0](https://gitee.com/koogua/course-tencent-cloud/releases/v1.1.0)(2020-10-08)

- 增加运营统计功能
- 增加课程资料功能
- 增加changelog
- 忽略schema
- 账户安全页面调整
- 简化部分路由
- 修复相关课程列表undefined问题

### [v1.0.0-beta1](https://gitee.com/koogua/course-tencent-cloud/releases/v1.0.0-beta1)(2020-09-26)

前台功能：

- 注册、登录、忘记密码
- 首页：轮播、新上课程、免费课程、会员课程
- 课程列表：多维度筛选，多维度排序
- 课程详情：章节，咨询，评价，相关课程，推荐课程，课程套餐
- 课时详情：点播，直播，图文
- 购买支付：课程，套餐，赞赏，会员
- 教师列表
- 群组列表
- 即时通讯
- 在线客服
- 全文检索：课程、群组、用户
- 个人主页：我的课程，我的收藏，我的好友，我的群组
- 会员中心：我的课程，我的收藏，我的咨询，我的评价，我的好友，我的群组，我的订单，我的退款，个人信息，账户安全
- 教学中心 ：我的课程，我的直播，我的咨询

后台功能：

- 课程管理：课程列表，课程搜索，添加课程，编辑课程，删除课程，课程分类 
- 套餐管理：套餐列表，添加套餐，编辑套餐，删除套餐
- 话题管理：话题列表，添加话题，编辑话题，删除话题
- 单页管理：单页列表，添加单页，编辑单页，删除单页
- 帮助管理：帮助列表，添加帮助，编辑帮助，删除帮助，帮助分类
- 学员管理：学员列表，搜索学员，添加学员，编辑学员，学习记录
- 咨询管理：咨询列表，搜索咨询，编辑咨询，删除咨询
- 评价管理：评价列表，搜索评价，编辑评价，删除评价
- 群组管理：群组列表，搜索群组，编辑群组，删除群组
- 轮播管理：轮播列表，编辑轮播，删除轮播
- 导航管理：导航列表，编辑导航，删除导航
- 订单管理：订单列表，搜索订单，订单详情
- 交易管理：交易列表，搜索交易，交易详情
- 退款管理：退款列表，搜索退款，退款详情，退款审核
- 用户管理：用户列表，编辑用户，添加用户
- 角色管理：角色列表，编辑角色，删除角色
- 操作记录：记录列表，搜索记录，记录详情
- 系统配置：网站，密钥，存储，点播，直播，短信，邮件，验证码，支付，会员，微聊
