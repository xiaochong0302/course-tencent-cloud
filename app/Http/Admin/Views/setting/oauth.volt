{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本设置</li>
            <li>QQ登录</li>
            <li>微信登录</li>
            <li>微博登录</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/oauth_local') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/oauth_qq') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/oauth_weixin') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/oauth_weibo') }}
            </div>
        </div>
    </div>

{% endblock %}
