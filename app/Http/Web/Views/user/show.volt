{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    {% set vip_flag = user.vip ? '<i class="layui-icon layui-icon-diamond vip-icon"></i>' : '' %}

    <div class="user-profile container clearfix">
        <div class="avatar">
            <img src="{{ user.avatar }}" alt="{{ user.name }}">
        </div>
        <div class="info">
            <h3>{{ user.name }} {{ vip_flag }}</h3>
            <p><span><i class="layui-icon layui-icon-location"></i></span><span>{{ user.location }}</span></p>
            <p><span><i class="layui-icon layui-icon-time"></i></span><span>{{ date('Y-m-d H:i',user.last_login_time) }}</span></p>
        </div>
        {% if user.about %}
            <div class="about">{{ user.about }}</div>
        {% endif %}
    </div>

    {% set course_url = url({'for':'web.user.courses','id':user.id}) %}
    {% set favorite_url = url({'for':'web.user.favorites','id':user.id}) %}
    {% set friend_url = url({'for':'web.user.friends','id':user.id}) %}

    <div class="container">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">课程</li>
                <li>收藏</li>
                <li>好友</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show" id="tab-courses" data-url="{{ course_url }}"></div>
                <div class="layui-tab-item" id="tab-favorites" data-url="{{ favorite_url }}"></div>
                <div class="layui-tab-item" id="tab-friends" data-url="{{ friend_url }}"></div>
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        if ($('#tab-courses').length > 0) {
            var $tabCourses = $('#tab-courses');
            helper.ajaxLoadHtml($tabCourses.attr('data-url'), $tabCourses.attr('id'));
        }
        if ($('#tab-favorites').length > 0) {
            var $tabFavorites = $('#tab-favorites');
            helper.ajaxLoadHtml($tabFavorites.attr('data-url'), $tabFavorites.attr('id'));
        }
    </script>

{% endblock %}