{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本设置</li>
            <li class="layui">模板消息</li>
            <li class="layui">自定义菜单</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/wechat_oa_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/wechat_oa_notice') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/wechat_oa_menu') }}
            </div>
        </div>
    </div>

{% endblock %}