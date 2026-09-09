{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本设置</li>
            <li>默认图片</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/storage_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/storage_image') }}
            </div>
        </div>
    </div>

{% endblock %}
