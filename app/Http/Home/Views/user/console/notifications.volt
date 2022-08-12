{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/notification') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">消息提醒</span>
                </div>
                <div class="notice-list">
                    {% for item in pager.items %}
                        {% set sender_url = url({'for':'home.user.show','id':item.sender.id}) %}
                        {% set receiver_url = url({'for':'home.user.show','id':item.receiver.id}) %}
                        <div class="comment-card notice-card">
                            <div class="avatar">
                                <a href="{{ sender_url }}" title="{{ item.sender.name }}" target="_blank">
                                    <img src="{{ item.sender.avatar }}!avatar_160" alt="{{ item.sender.name }}">
                                </a>
                            </div>
                            <div class="info">
                                <div class="user">
                                    <a href="{{ sender_url }}" target="_blank">{{ item.sender.name }}</a>
                                </div>
                                <div class="content">{{ event_info(item) }}</div>
                                <div class="footer">
                                    <div class="left">
                                        <div class="column">
                                            <span class="time" title="{{ date('Y-m-d H:i:s',item.create_time) }}">{{ item.create_time|time_ago }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {% endfor %}
                </div>
                {{ partial('partials/pager') }}
            </div>
        </div>
    </div>

{% endblock %}