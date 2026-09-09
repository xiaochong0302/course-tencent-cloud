{% extends 'templates/main.volt' %}

{% block content %}

    {% set template = sms.template|json_decode %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本设置</li>
            <li>模板设置</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/sms_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/sms_template') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('admin/js/copy.js') }}

{% endblock %}
