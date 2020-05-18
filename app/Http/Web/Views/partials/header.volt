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
    <form class="layui-form" action="{{ url({'for':'web.search.show'}) }}">
        <div class="layui-inline">
            <input type="text" name="q" placeholder="请输入课程关键字...">
        </div>
    </form>
</div>

<div class="user layui-layout-right">
    {% if auth_user %}
        <ul class="layui-nav">
            <li class="layui-nav-item"><a href="{{ url({'for':'web.my.courses'}) }}">消息</a></li>
            <li class="layui-nav-item">
                <a href="javascript:">{{ auth_user.name }}</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'web.my.courses'}) }} }}">我的课程</a></dd>
                    <dd><a href="{{ url({'for':'web.my.courses'}) }} }}">我的收藏</a></dd>
                    <dd><a href="{{ url({'for':'web.my.courses'}) }} }}">我的咨询</a></dd>
                    <dd><a href="{{ url({'for':'web.my.courses'}) }} }}">我的订单</a></dd>
                    <dd><a href="{{ url({'for':'web.my.courses'}) }} }}">个人设置</a></dd>
                    <dd><a href="{{ url({'for':'web.my.courses'}) }} }}">退出登录</a></dd>
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
