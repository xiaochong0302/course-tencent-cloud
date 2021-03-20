### [v1.2.9](https://gitee.com/koogua/course-tencent-cloud/releases/v1.2.9)(2021-03-22)

### 更新

- 增加限时秒杀功能
- 更新phinx默认环境配置项
- 重构轮播图数据结构
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
