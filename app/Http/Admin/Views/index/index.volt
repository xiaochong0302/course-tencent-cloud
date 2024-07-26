<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>管理后台</title>
    {{ icon_link('favicon.ico') }}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('admin/css/common.css') }}
    {{ js_include('lib/layui/layui.js') }}
    {{ js_include('admin/js/index.js') }}
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">{{ site_info.title }}</div>
        <div class="kg-side-menu-bar">
            <a href="javascript:" title="关闭左侧菜单"><i class="layui-icon layui-icon-spread-left"></i></a>
        </div>
        <ul class="layui-nav layui-layout-left kg-nav-module">
            <li class="layui-nav-item">
                <a href="/admin">首页</a>
            </li>
            {% for item in top_menus %}
                <li data-module="module-{{ item.id }}" class="layui-nav-item">
                    <a href="javascript:">{{ item.title }}</a>
                </li>
            {% endfor %}
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="{{ url({'for':'home.index'}) }}" target="_blank">前台首页</a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:">用户服务</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'admin.koogua.wiki'}) }}" target="_blank">系统文档</a></dd>
                    <dd><a href="{{ url({'for':'admin.koogua.community'}) }}" target="_blank">开源社区</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:">{{ auth_user.name }}</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ url({'for':'home.uc.profile'}) }}" target="_blank">基本资料</a></dd>
                    <dd><a href="{{ url({'for':'home.uc.account'}) }}" target="_blank">安全设置</a></dd>
                    <dd><a href="{{ url({'for':'admin.logout'}) }}">退出登录</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            {% for key,level in left_menus %}
                <ul class="layui-nav layui-nav-tree {% if key != 0 %}layui-hide{% endif %}" lay-shrink="all" data-module="module-{{ level.id }}">
                    {% for key2,level2 in level.children %}
                        <li class="layui-nav-item {% if key2 == 0 %}layui-nav-itemed{% endif %}">
                            <a href="javascript:">{{ level2.title }}</a>
                            <dl class="layui-nav-child">
                                {% for level3 in level2.children %}
                                    <dd><a target="content" href="{{ level3.url }}">{{ level3.title }}</a></dd>
                                {% endfor %}
                            </dl>
                        </li>
                    {% endfor %}
                </ul>
            {% endfor %}
        </div>
    </div>
    <div class="layui-body">
        <iframe name="content" style="width:100%;height:100%;border:0;" src="{{ url({'for':'admin.main'}) }}"></iframe>
    </div>
</div>
</body>
</html>