{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/group') }}

    {% set joined_url = url({'for':'desktop.my.groups'},{'type':'joined'}) %}
    {% set owned_url = url({'for':'desktop.my.groups'},{'type':'owned'}) %}
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
                    {{ partial('my/groups_owned') }}
                {% else %}
                    {{ partial('my/groups_joined') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('desktop/js/my.js') }}

{% endblock %}