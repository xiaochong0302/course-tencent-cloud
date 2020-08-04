{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {% set vip_flag = user.vip ? '<i class="layui-icon layui-icon-diamond icon-vip"></i>' : '' %}

    <div class="user-profile wrap clearfix">
        <div class="avatar">
            <img src="{{ user.avatar }}" alt="{{ user.name }}">
        </div>
        <div class="info">
            <p><span class="name">{{ user.name }}</span> {{ vip_flag }}</p>
            <p><span><i class="layui-icon layui-icon-location"></i></span><span>{{ user.location }}</span></p>
            <p><span><i class="layui-icon layui-icon-time"></i></span><span>{{ date('Y-m-d H:i',user.active_time) }}</span></p>
        </div>
        {% if user.about %}
            <div class="about">{{ user.about }}</div>
        {% endif %}
    </div>

    {% set show_tab_courses = user.course_count > 0 %}
    {% set show_tab_favorites = user.favorite_count > 0 %}
    {% set show_tab_friends = user.friend_count > 0 %}
    {% set show_tab_groups = user.group_count > 0 %}

    {% set courses_url = url({'for':'web.user.courses','id':user.id}) %}
    {% set favorites_url = url({'for':'web.user.favorites','id':user.id}) %}
    {% set friends_url = url({'for':'web.user.friends','id':user.id}) %}
    {% set groups_url = url({'for':'web.user.groups','id':user.id}) %}

    <div class="tab-wrap">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">课程<span class="tab-count">{{ user.course_count }}</span></li>
                {% if show_tab_favorites %}
                    <li>收藏<span class="tab-count">{{ user.favorite_count }}</span></li>
                {% endif %}
                {% if show_tab_friends %}
                    <li>好友<span class="tab-count">{{ user.friend_count }}</span></li>
                {% endif %}
                {% if show_tab_groups %}
                    <li>群组<span class="tab-count">{{ user.group_count }}</span></li>
                {% endif %}
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show" id="tab-courses" data-url="{{ courses_url }}"></div>
                {% if show_tab_favorites %}
                    <div class="layui-tab-item" id="tab-favorites" data-url="{{ favorites_url }}"></div>
                {% endif %}
                {% if show_tab_friends %}
                    <div class="layui-tab-item" id="tab-friends" data-url="{{ friends_url }}"></div>
                {% endif %}
                {% if show_tab_groups %}
                    <div class="layui-tab-item" id="tab-groups" data-url="{{ groups_url }}"></div>
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/user.show.js') }}
    {{ js_include('web/js/im.apply.js') }}

{% endblock %}