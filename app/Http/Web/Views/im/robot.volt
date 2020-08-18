{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>智能聊天</cite></a>
        </span>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="cs_user.id" value="{{ cs_user.id }}">
        <input type="hidden" name="cs_user.name" value="{{ cs_user.name }}">
        <input type="hidden" name="cs_user.avatar" value="{{ cs_user.avatar }}">
        <input type="hidden" name="cs_user.welcome" value="我是智能聊天机器人，试试和我聊天吧！">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/im.cs.js') }}

{% endblock %}