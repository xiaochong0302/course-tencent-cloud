{% set tc_val = request.get('tc','int','all') %}
{% set sc_val = request.get('sc','int','all') %}
{% set sort_val = request.get('sort','trim','latest') %}

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
</div>
