{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>名师</cite></a>
    </div>

    {% if pager.total_pages > 0 %}
        <div class="teach-user-list clearfix">
            <div class="layui-row layui-col-space20">
                {% for item in pager.items %}
                    {% set user_title = item.title ? item.title : '小小教书匠' %}
                    {% set user_about = item.about ? item.about|e : '这个人很懒，什么都没留下' %}
                    {% set user_url = url({'for':'web.teacher.show','id':item.id}) %}
                    <div class="layui-col-md3">
                        <div class="user-card">
                            <div class="avatar">
                                <a href="{{ user_url }}" title="{{ user_about }}"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                            </div>
                            <div class="name layui-elip">
                                <a href="{{ user_url }}">{{ item.name }}</a>
                            </div>
                            <div class="title layui-elip">{{ user_title }}</div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
        {{ partial('partials/pager') }}
    {% endif %}

{% endblock %}