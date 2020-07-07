{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layui-collapse">
        {% for help in helps %}
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">{{ help.title }}</h2>
                <div class="layui-colla-content layui-show">{{ help.content }}</div>
            </div>
        {% endfor %}
    </div>

{% endblock %}