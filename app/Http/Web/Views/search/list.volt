{% extends 'templates/base.volt' %}

{% block content %}

    <div class="search-course-list">
        {% for item in pager.items %}
            <div class="search-course-card">
                <div class="cover">
                    <a href="{{ url({'for':'web.course.show','id':item.id}) }}" title="{{ item.title|e }}">
                        <img src="{{ item.cover }}!cover_270" alt="{{ item.title|e }}">
                    </a>
                </div>
                <div class="info">
                    <div class="title">
                        <a href="{{ url({'for':'web.course.show','id':item.id}) }}" title="{{ item.title|e }}">{{ item.title }}</a>
                    </div>
                    <div class="meta">
                        {% if item.market_price > 0 %}
                            <span class="price">￥{{ item.market_price }}</span>
                            <span class="level">中级</span>
                            <span class="lesson">{{ item.lesson_count }}节课</span>
                            <span class="user">{{ item.user_count }}人购买</span>
                        {% else %}
                            <span class="free">免费</span>
                            <span class="level">中级</span>
                            <span class="lesson">{{ item.lesson_count }}节课</span>
                            <span class="user">{{ item.user_count }}人报名</span>
                        {% endif %}
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>

    <div class="pager">
        {{ partial('partials/pager') }}
    </div>

{% endblock %}