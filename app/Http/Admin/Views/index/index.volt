<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>管理后台</title>
    {{ stylesheet_link('lib/layui/css/layui.css') }}
    {{ stylesheet_link('admin/css/style.css') }}
    {{ javascript_include('lib/layui/layui.js') }}
    {{ javascript_include('admin/js/index.js') }}
</head>

<body class="layui-layout-body">

<div class="layui-layout layui-layout-admin">

    <div class="layui-header">
        <div class="layui-logo">COURSE ADMIN</div>
        <div class="kg-side-menu-bar">
            <a href="javascript:"><i class="layui-icon layui-icon-spread-left"></i></a>
        </div>
        <ul class="layui-nav layui-layout-left kg-nav-module">
            {% for item in top_menus %}
                <li nav-module="module-{{ item.id }}" class="layui-nav-item">
                    <a href="javascript:">{{ item.label }}</a>
                </li>
            {% endfor %}
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:">{{ auth_user.name }}</a>
                <dl class="layui-nav-child">
                    <dd><a target="content" href="#">基本资料</a></dd>
                    <dd><a target="content" href="#">安全设置</a></dd>
                    <dd><a href="{{ url({'for':'admin.logout'}) }}">退出登录</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="{{ url({'for':'web.index'}) }}" target="_blank">前台</a>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            {% for key,level in left_menus %}
                <ul class="layui-nav layui-nav-tree {% if key != 0 %}layui-hide{% endif %}" nav-module="module-{{ level.id }}" lay-shrink="all">
                    {% for key2,level2 in level.child %}
                        <li class="layui-nav-item {% if key2 == 0 %}layui-nav-itemed{% endif %}">
                            <a href="javascript:">{{ level2.label }}</a>
                            <dl class="layui-nav-child">
                                {% for level3 in level2.child %}
                                    <dd><a target="content" href="{{ level3.url }}">{{ level3.label }}</a></dd>
                                {% endfor %}
                            </dl>
                        </li>
                    {% endfor %}
                </ul>
            {% endfor %}
        </div>
    </div>

    <div class="layui-body">
        <iframe name="content" width="100%" height="100%" frameborder="0" src="{{ url({'for':'admin.main'}) }}"></iframe>
    </div>

    <div class="layui-footer">
        © 2018 <a href="http://koogua.com"><b>koogua.com</b></a> all rights reserved
    </div>

</div>

</body>

</html>