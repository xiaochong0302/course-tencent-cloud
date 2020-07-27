<div class="logo"></div>

<div class="top-nav">
    <ul class="layui-nav">
        {% for nav in site_navs.top %}
            <li class="layui-nav-item">
                <a href="{{ nav.url }}" target="{{ nav.target }}">{{ nav.name }}</a>
                {% if nav.children %}
                    <dl class="layui-nav-child">
                        {% for child in nav.children %}
                            <dd><a href="{{ child.url }}" target="{{ child.target }}">{{ child.name }}</a></dd>
                        {% endfor %}
                    </dl>
                {% endif %}
            </li>
        {% endfor %}
    </ul>
</div>

<div class="search">
    <form class="layui-form" action="{{ url({'for':'web.search.list'}) }}">
        <input class="layui-input" type="text" name="query" maxlength="30" autocomplete="off" placeholder="请输入课程关键字..." value="{{ request.get('query')|striptags }}">
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
