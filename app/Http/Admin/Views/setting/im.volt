{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本设置</li>
            <li>在线客服</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/im_main') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/im_cs') }}
            </div>
        </div>
    </div>

{% endblock %}