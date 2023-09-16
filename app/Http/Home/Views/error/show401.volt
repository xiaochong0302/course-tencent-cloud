{% extends 'templates/error.volt' %}

{% block content %}

    <div class="layui-fluid">
        <div class="kg-tips">
            <i class="layui-icon layui-icon-face-surprised"></i>
            <div class="message">无操作权限，请先登录认证</div>
            <div class="layui-text">
                <h1>
                    <span class="layui-anim layui-anim-loop">4</span>
                    <span class="layui-anim layui-anim-loop">0</span>
                    <span class="layui-anim layui-anim-loop">1</span>
                </h1>
            </div>
        </div>
    </div>

{% endblock %}