## 酷瓜云课堂

![酷瓜云网课GPL协议开源](https://images.gitee.com/uploads/images/2020/1127/092621_3805cf8f_23592.png)

#### 项目介绍

酷瓜云课堂，依托腾讯云基础服务架构，采用C扩展框架Phalcon开发，GPL-2.0开源协议，致力开源网课系统，开源网校系统，开源在线教育系统。

![](https://img.shields.io/static/v1?label=release&message=1.2.1&color=blue)
![](https://img.shields.io/static/v1?label=stars&message=112&color=blue)
![](https://img.shields.io/static/v1?label=forks&message=41&color=blue)
![](https://img.shields.io/static/v1?label=license&message=GPL-2.0&color=blue)

#### 系统功能

实现了点播、直播、专栏、会员、微聊等，是一个完整的产品，具体功能我也不想写一大堆，自己体验吧！

友情提示：

- 系统配置低（1核 1G 1M 跑多个容器），切莫压测
- 课程数据来源于网络（无实质内容），切莫购买
- 管理后台已禁止数据提交，私密配置已过滤

演示帐号：**13507083515 / 123456** （前后台通用）

桌面端演示：

- [前台演示](https://ctc.koogua.com)
- [后台演示](https://ctc.koogua.com/admin)

移动端演示：

![移动端二维码](https://images.gitee.com/uploads/images/2020/1127/093203_265221a2_23592.png)

支付流程演示：

- [MySQL提升课程全面讲解MySQL架构设计（0.01元）](https://ctc.koogua.com/order/confirm?item_id=1390&item_type=1)
- [Nginx入门到实践Nginx中间件（0.01元）](https://ctc.koogua.com/order/confirm?item_id=1286&item_type=1)
- [数据库与中间件的基础必修课（0.02元）](https://ctc.koogua.com/order/confirm?item_id=80&item_type=2)

Tips: 测试支付请用手机号注册一个新账户，以便接收订单通知，以及避免课程无法购买
 
#### 项目组件

- 后台框架：[phalcon 3.4.5](https://phalcon.io)
- 前端框架：[layui 2.5.6](https://layui.com)， [layim 3.9.5](https://www.layui.com/layim)（已授权）
- 全文检索：[xunsearch 1.4.9](http://www.xunsearch.com)
- 即时通讯：[workerman 3.5.22](https://workerman.net)
- 基础依赖：[php7.3](https://php.net)， [mysql5.7](https://mysql.com)， [redis5.0](https://redis.io)

#### 安装指南

- [运行环境搭建](https://gitee.com/koogua/course-tencent-cloud-docker)
- [系统服务配置](https://gitee.com/koogua/course-tencent-cloud/wikis)

#### 开发计划

- 桌面端：进行中
- 移动端：进行中
- 小程序：待启动

#### 意见反馈

- [在线反馈](https://gitee.com/koogua/course-tencent-cloud/issues)（推荐）
- QQ交流群: 787363898

#### 通过这个项目能学到什么？

- 项目规划，phalcon，缓存，JWT，即时通讯，全文检索
- docker，supervisor，devops
- git，linux，php，mysql，redis，nginx

#### 有阿里云版吗？

阿里云版规划中，之前阿里云服务过期未续费，所以腾讯云版本先出。

#### 代码有加密吗？

所有代码都公开（授权代码除外，例如layim），没有所谓的商业版和付费插件。

#### 有商业服务吗？

生存才能发展，我们目前提供的服务包括：

- 系统安装
- 系统定制
- 企业授权

