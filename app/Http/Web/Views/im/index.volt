{% extends 'templates/main.volt' %}

{% block content %}

    <div class="tab-wrap">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">活跃群组</li>
                <li>活跃用户</li>
                <li>新进群组</li>
                <li>新进用户</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show"></div>
                <div class="layui-tab-item"></div>
                <div class="layui-tab-item"></div>
                <div class="layui-tab-item"></div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/im.js') }}

{% endblock %}