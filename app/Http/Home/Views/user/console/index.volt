{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro vip_info(user) %}
        {% set vip_url = url({'for':'home.vip.index'}) %}
        {% if user.vip == 1 %}
            <a class="layui-badge layui-bg-orange" title="到期时间：{{ date('Y-m-d',user.vip_expiry_time) }}" href="{{ vip_url }}">会员</a>
        {% else %}
            <a class="layui-badge layui-bg-gray" title="开通会员" href="{{ vip_url }}">会员</a>
        {% endif %}
    {%- endmacro %}

    {% set withdraw_setting = setting('withdraw') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-summary">
                    <div class="profile-column">
                        <div class="avatar">
                            <img src="{{ user.avatar }}!avatar_160" alt="{{ user.name }}">
                        </div>
                        <div class="info">
                            <div class="name"><span>{{ user.name }}</span> {{ vip_info(user) }}</div>
                            <div class="title">{{ user.title|default('小小书童') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-card my-stats">
                <div class="layui-card-header">在线记录</div>
                <div class="layui-card-body">
                    <div class="my-online-list">
                        {% for item in online_stats %}
                            {% if item.online == 1 %}
                                <div class="day active" title="{{ date('Y-m-d H:i',item.active_time) }}">{{ item.day }}</div>
                            {% else %}
                                <div class="day">{{ item.day }}</div>
                            {% endif %}
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}
