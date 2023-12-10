{% set logo_img = site_info.logo ? image(site_info.logo,false) : image('logo.png') %}
{% set logo_link = site_info.url ? site_info.url : '/' %}

<div class="logo">
    <a href="{{ logo_link }}">{{ logo_img }}</a>
</div>

<div class="top-nav">
    <ul class="layui-nav">
        {% for nav in navs.top %}
            <li class="layui-nav-item">
                <a href="{{ nav.url }}" class="nav-{{ nav.id }}" target="{{ nav.target }}">{{ nav.name }}</a>
                {% if nav.children %}
                    <dl class="layui-nav-child">
                        {% for child in nav.children %}
                            <dd><a href="{{ child.url }}" class="nav-{{ child.id }}" target="{{ child.target }}">{{ child.name }}</a></dd>
                        {% endfor %}
                    </dl>
                {% endif %}
            </li>
        {% endfor %}
    </ul>
</div>

<div class="user">
    <ul class="layui-nav">
        <li class="layui-nav-item">
            <a class="nav-search" href="javascript:" data-url="{{ url({'for':'home.search.index'}) }}"><i class="layui-icon layui-icon-search"></i> 搜索</a>
        </li>
        <li class="layui-nav-item">
            <a class="nav-vip" href="{{ url({'for':'home.vip.index'}) }}"><i class="layui-icon layui-icon-diamond"></i> 会员</a>
        </li>
        {% if auth_user.id > 0 %}
            <li class="layui-nav-item">
                <a href="javascript:"><i class="layui-icon layui-icon-add-circle"></i> 发布</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'home.question.add'}) }}" target="_blank">提问题</a></dd>
                    <dd><a href="{{ url({'for':'home.article.add'}) }}" target="_blank">写文章</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="{{ url({'for':'home.uc.notifications'}) }}" target="notify"><i class="layui-icon layui-icon-notice"></i> 消息<span id="notify-dot"></span></a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:">{{ auth_user.name }}</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'home.user.show','id':auth_user.id}) }}">我的主页</a></dd>
                    {% if auth_user.edu_role == 2 %}
                        <dd><a href="{{ url({'for':'home.tc.index'}) }}">教学中心</a></dd>
                    {% endif %}
                    <dd><a href="{{ url({'for':'home.uc.index'}) }}">用户中心</a></dd>
                    <dd><a href="{{ url({'for':'home.account.logout'}) }}">退出登录</a></dd>
                </dl>
            </li>
        {% else %}
            <li class="layui-nav-item"><a href="{{ url({'for':'home.account.login'}) }}">登录</a></li>
            <li class="layui-nav-item"><a href="{{ url({'for':'home.account.register'}) }}">注册</a></li>
        {% endif %}
    </ul>
</div>