{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>帮助中心</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="wrap">
                <div class="layui-collapse">
                    {% for item in items %}
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">{{ item.category.name }}</h2>
                            <div class="layui-colla-content layui-show">
                                <ul class="help-list">
                                    {% for help in item.helps %}
                                        {% set show_url = url({'for':'home.help.show','id':help.id}) %}
                                        <li><a href="{{ show_url }}"><i class="layui-icon layui-icon-right"></i>{{ help.title }}</a></li>
                                    {% endfor %}
                                </ul>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="layui-card cs-sidebar">
                <div class="layui-card-header">客户服务</div>
                <div class="layui-card-body">
                    <p>没解决你的疑问？试试联系客服吧！</p>
                    {% if im_info.cs.enabled == 1 %}
                        <p class="center">
                            <button class="layui-btn layui-btn-sm btn-cs">联系客服</button>
                        </p>
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/help.js') }}

{% endblock %}