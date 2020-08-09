{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>帮助</cite></a>
        </span>
    </div>

    <div class="page-info wrap">
        <div class="layui-collapse">
            {% for help in helps %}
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title">{{ help.title }}</h2>
                    <div class="layui-colla-content layui-show">{{ help.content }}</div>
                </div>
            {% endfor %}
        </div>
    </div>

{% endblock %}