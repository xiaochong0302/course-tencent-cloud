{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本设置</li>
            <li class="layui">模板消息</li>
            <li class="layui">自定义菜单</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/wechat_oa_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/wechat_oa_notice') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/wechat_oa_menu') }}
            </div>
        </div>
    </div>

{% endblock %}
