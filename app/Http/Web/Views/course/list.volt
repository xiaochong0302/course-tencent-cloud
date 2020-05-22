{% extends 'templates/base.volt' %}

{% block content %}

    <div class="course-filter">
        <div class="filter-group">
            <div class="title">方向</div>
            <div class="content">
                {% for category in top_categories %}
                    {% set class = request.get('tc','int','all') == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
        {% if sub_categories %}
            <div class="filter-group">
                <div class="title">分类</div>
                <div class="content">
                    {% for category in sub_categories %}
                        {% set class = request.get('sc','int','all') == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                        <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                    {% endfor %}
                </div>
            </div>
        {% endif %}
        <div class="filter-group">
            <div class="title">类型</div>
            <div class="content">
                {% for model in models %}
                    {% set class = request.get('model','trim','all') == model.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ model.url }}">{{ model.name }}</a>
                {% endfor %}
            </div>
        </div>
        <div class="filter-group">
            <div class="title">难度</div>
            <div class="content">
                {% for level in levels %}
                    {% set class = request.get('level','trim','all') == level.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ level.url }}">{{ level.name }}</a>
                {% endfor %}
            </div>
        </div>
        <div class="filter-group">
            <div class="title">排序</div>
            <div class="content">
                {% for sort in sorts %}
                    {% set class = request.get('sort','trim','score') == sort.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ sort.url }}">{{ sort.name }}</a>
                {% endfor %}
            </div>
        </div>
    </div>

    <div class="course-list clearfix">
        {% for item in pager.items %}
            <div class="course-card">
                <div class="cover">
                    <a href="{{ url({'for':'web.course.show','id':item.id}) }}" title="{{ item.title }}">
                        <img src="{{ item.cover }}!cover_270" alt="{{ item.title }}">
                    </a>
                </div>
                <div class="title">
                    <a href="{{ url({'for':'web.course.show','id':item.id}) }}" title="{{ item.title }}">{{ substr(item.title,0,15) }}</a>
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
        {% endfor %}
    </div>

    <div class="pager">
        {{ partial('partials/pager') }}
    </div>

{% endblock %}