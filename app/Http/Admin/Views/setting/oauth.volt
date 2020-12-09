{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">QQ登录</li>
            <li>微信登录</li>
            <li>新浪微博</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/oauth_qq') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/oauth_weixin') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/oauth_weibo') }}
            </div>
        </div>
    </div>

{% endblock %}