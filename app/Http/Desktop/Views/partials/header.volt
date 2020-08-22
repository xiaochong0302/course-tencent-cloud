<div class="logo"></div>

<div class="top-nav">
    <ul class="layui-nav">
        {% for nav in navs.top %}
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

{% set s_type = request.get('type',['trim','string'],'course') %}
{% set s_query = request.get('query',['trim','striptags'],'') %}
{% set s_url = url({'for':'desktop.search.index'}) %}

<div class="user layui-layout-right">
    <ul class="layui-nav">
        <li class="layui-nav-item">
            <a href="javascript:" class="kg-search" data-type="{{ s_type }}" data-query="{{ s_query }}" data-url="{{ s_url }}"><i class="layui-icon layui-icon-search"></i> 搜索</a>
        </li>
        <li class="layui-nav-item">
            <a href="{{ url({'for':'desktop.im.index'}) }}" target="im">微聊<span class="layui-badge-dot"></span></a>
        </li>
        {% if auth_user.id > 0 %}
            <li class="layui-nav-item">
                <a href="javascript:">{{ auth_user.name }}</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'desktop.user.show','id':auth_user.id}) }}">我的主页</a></dd>
                    {% if auth_user.edu_role == 2 %}
                        <dd><a href="{{ url({'for':'desktop.teaching.index'}) }}">教学中心</a></dd>
                    {% endif %}
                    <dd><a href="{{ url({'for':'desktop.my.index'}) }}">用户中心</a></dd>
                    <dd><a href="{{ url({'for':'desktop.account.logout'}) }}">退出登录</a></dd>
                </dl>
            </li>
        {% else %}
            <li class="layui-nav-item"><a href="{{ url({'for':'desktop.account.login'}) }}">登录</a></li>
            <li class="layui-nav-item"><a href="{{ url({'for':'desktop.account.register'}) }}">注册</a></li>
        {% endif %}
    </ul>
</div>