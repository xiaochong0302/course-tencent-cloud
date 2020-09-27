{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/group') }}

    {% set joined_url = url({'for':'home.uc.groups'},{'scope':'joined'}) %}
    {% set owned_url = url({'for':'home.uc.groups'},{'scope':'owned'}) %}
    {% set joined_class = scope == 'joined' ? 'layui-btn layui-btn-xs' : 'none' %}
    {% set owned_class = scope == 'owned' ? 'layui-btn layui-btn-xs' : 'none' %}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="my-nav">
                <span class="title">我的群组</span>
                <a class="{{ joined_class }}" href="{{ joined_url }}">参加的</a>
                <a class="{{ owned_class }}" href="{{ owned_url }}">管理的</a>
            </div>
            <div class="my-group-wrap wrap">
                {% if scope == 'owned' %}
                    {{ partial('user/console/groups_owned') }}
                {% else %}
                    {{ partial('user/console/groups_joined') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}