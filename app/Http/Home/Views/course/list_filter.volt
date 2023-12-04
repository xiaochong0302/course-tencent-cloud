{% set tc_val = request.get('tc','int','all') %}
{% set sc_val = request.get('sc','int','all') %}
{% set model_val = request.get('model','trim','all') %}
{% set level_val = request.get('level','trim','all') %}
{% set sort_val = request.get('sort','trim','score') %}

<div class="filter-toggle">
    <span class="layui-icon layui-icon-up"></span>
</div>

<div class="filter-wrap wrap">
    {% if top_categories %}
        <div class="filter-group">
            <div class="title">方向</div>
            <div class="content">
                {% for category in top_categories %}
                    {% set class = tc_val == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
    {% endif %}
    {% if sub_categories %}
        <div class="filter-group">
            <div class="title">分类</div>
            <div class="content">
                {% for category in sub_categories %}
                    {% set class = sc_val == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                    <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
                {% endfor %}
            </div>
        </div>
    {% endif %}
    <div class="filter-group">
        <div class="title">类型</div>
        <div class="content">
            {% for model in models %}
                {% set class = model_val == model.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ model.url }}">{{ model.name }}</a>
            {% endfor %}
        </div>
    </div>
    <div class="filter-group">
        <div class="title">难度</div>
        <div class="content">
            {% for level in levels %}
                {% set class = level_val == level.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ level.url }}">{{ level.name }}</a>
            {% endfor %}
        </div>
    </div>
</div>

<div class="filter-sort wrap">
    {% for sort in sorts %}
        {% set class = sort_val == sort.id ? 'layui-btn layui-btn-xs' : 'none' %}
        <a class="{{ class }}" href="{{ sort.url }}">{{ sort.name }}</a>
    {% endfor %}
</div>
