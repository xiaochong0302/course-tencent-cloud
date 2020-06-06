<div class="logo"></div>

<div class="top-nav">
    <ul class="layui-nav">
        {% for nav in site_navs.top %}
            {% if nav.children %}
                <li class="layui-nav-item">
                    <a href="javascript:">{{ nav.name }}</a>
                    <dl class="layui-nav-child">
                        {% for child in nav.children %}
                            <dd><a href="{{ child.url }}" target="{{ child.target }}">{{ child.name }}</a></dd>
                        {% endfor %}
                    </dl>
                </li>
            {% else %}
                <li class="layui-nav-item">
                    <a href="{{ nav.url }}">{{ nav.name }}</a>
                </li>
            {% endif %}
        {% endfor %}
    </ul>
</div>

<div class="search">
    <form class="layui-form" action="{{ url({'for':'web.search.list'}) }}">
        <input class="layui-input" type="text" name="query" value="{{ request.get('query')|striptags }}" autocomplete="off" placeholder="请输入课程关键字...">
    </form>
</div>

<div class="user layui-layout-right">
    {% if auth_user.id > 0 %}
        <ul class="layui-nav">
            <li class="layui-nav-item"><a href="#">消息</a></li>
            <li class="layui-nav-item">
                <a href="javascript:">{{ auth_user.name }}</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'web.my.home'}) }}">我的主页</a></dd>
                    <dd><a href="{{ url({'for':'web.my.profile'}) }}">个人设置</a></dd>
                    <dd><a href="{{ url({'for':'web.account.logout'}) }}">退出登录</a></dd>
                </dl>
            </li>
        </ul>
    {% else %}
        <ul class="layui-nav">
            <li class="layui-nav-item"><a href="{{ url({'for':'web.account.login'}) }}">登录</a></li>
            <li class="layui-nav-item"><a href="{{ url({'for':'web.account.register'}) }}">注册</a></li>
        </ul>
    {% endif %}
</div>
