{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>帮助</cite></a>
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
                                        <li><a href="{{ url({'for':'web.help.show','id':help.id}) }}"><i class="layui-icon layui-icon-right"></i>{{ help.title }}</a></li>
                                    {% endfor %}
                                </ul>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="layui-card">
                <div class="layui-card-header">意见反馈</div>
                <div class="layui-card-body">
                    <form class="layui-form">
                        <textarea name="content" class="layui-textarea" lay-verify="required"></textarea>
                    </form>
                </div>
            </div>
        </div>
    </div>

{% endblock %}