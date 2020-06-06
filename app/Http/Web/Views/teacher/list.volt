{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>名师</cite></a>
    </div>

    {% if pager.total_pages > 0 %}
        <div class="teacher-list clearfix">
            {% for item in pager.items %}
                {% set teacher_title = item.title ? item.title : '小小教书匠' %}
                {% set teacher_url = url({'for':'web.teacher.show','id':item.id}) %}
                <div class="teacher-card" title="{{ item.about|e }}">
                    <div class="avatar">
                        <a href="{{ teacher_url }}"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                    </div>
                    <div class="name"><a href="{{ teacher_url }}">{{ item.name }}</a></div>
                    <div class="title">{{ teacher_title }}</div>
                </div>
            {% endfor %}
        </div>
        {{ partial('partials/pager') }}
    {% endif %}

{% endblock %}