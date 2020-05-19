{% extends 'templates/base.volt' %}

{% block content %}

    <div class="course-filter">
        <div class="group">
            <div class="title">方向</div>
            <div class="content">
                {% for category in top_categories %}
                    {% set class = request.get('tc') == category.id ? 'layui-badge active' : 'layui-badge' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
        <div class="group">
            <div class="title">分类</div>
            <div class="content">
                {% for category in sub_categories %}
                    {% set class = request.get('sc') == category.id ? 'layui-badge active' : 'layui-badge' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
        <div class="group">
            <div class="title">类型</div>
            <div class="content">
                {% for model in models %}
                    {% set class = request.get('model') == model.id ? 'layui-badge active' : 'layui-badge' %}
                    <a class="{{ class }}" href="{{ model.url }}">{{ model.name }}</a>
                {% endfor %}
            </div>
        </div>
        <div class="group">
            <div class="title">难度</div>
            <div class="content">
                {% for level in levels %}
                    {% set class = request.get('level') == level.id ? 'layui-badge active' : 'layui-badge' %}
                    <a class="{{ class }}" href="{{ level.url }}">{{ level.name }}</a>
                {% endfor %}
            </div>
        </div>
    </div>

    <div class="course-sort">

    </div>

    <div class="course-list">
        {% for item in pager.items %}
            <div class="course-card">
                <div class="cover"></div>
                <div class="title">{{ item['title'] }}</div>
                <div class="info"></div>
            </div>
        {% endfor %}
    </div>

    <div class="pager">
        {{ partial('partials/pager') }}
    </div>

{% endblock %}