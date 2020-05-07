{% extends 'templates/base.volt' %}

{% block content %}

    {%- macro model_info(value) %}
        {% if value == 'vod' %}
            <span class="layui-badge layui-bg-green">点播{{ request.get('id') }}</span>
        {% elseif value == 'live' %}
            <span class="layui-badge layui-bg-blue">直播</span>
        {% elseif value == 'read' %}
            <span class="layui-badge layui-bg-black">图文</span>
        {% endif %}
    {%- endmacro %}

    <div class="model">{{ model_info('vod') }}</div>
    <h1>I am body</h1>
    <h2>ID:{{ request.get('id') }}</h2>

{% endblock %}