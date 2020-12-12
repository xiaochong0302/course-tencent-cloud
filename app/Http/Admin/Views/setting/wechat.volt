{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">公众号</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/wechat_oa') }}
            </div>
        </div>
    </div>

{% endblock %}