{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 'course' %}
            <span class="layui-badge layui-bg-green">课</span>
        {% elseif value == 'chat' %}
            <span class="layui-badge layui-bg-blue">聊</span>
        {% endif %}
    {%- endmacro %}

    {% set joined_url = url({'for':'web.my.groups'},{'type':'joined'}) %}
    {% set owned_url = url({'for':'web.my.groups'},{'type':'owned'}) %}
    {% set joined_class = type == 'joined' ? 'layui-btn layui-btn-xs' : 'none' %}
    {% set owned_class = type == 'owned' ? 'layui-btn layui-btn-xs' : 'none' %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="my-nav">
                <span class="title">我的群组</span>
                <a class="{{ joined_class }}" href="{{ joined_url }}">参加的</a>
                <a class="{{ owned_class }}" href="{{ owned_url }}">管理的</a>
            </div>
            <div class="my-group-wrap wrap">
                {% if type == 'owned' %}
                    {{ partial('my/im_groups_owned') }}
                {% else %}
                    {{ partial('my/im_groups_joined') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/my.js') }}

{% endblock %}