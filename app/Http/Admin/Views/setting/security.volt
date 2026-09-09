{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">注册控制</li>
            <li>访问控制</li>
            <li>限流控制</li>
            <li>黑名单</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/security_register') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/security_access') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/security_throttle') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/security_blacklist') }}
            </div>
        </div>
    </div>

{% endblock %}
