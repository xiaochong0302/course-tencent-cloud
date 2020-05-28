<div class="course-filter module">
    <div class="filter-group">
        <div class="title">方向</div>
        <div class="content">
            {% set tc_val = request.get('tc','int','all') %}
            {% for category in top_categories %}
                {% set class = tc_val == category.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ category.url }}">{{ category.name }}</a>
            {% endfor %}
        </div>
    </div>
    {% if sub_categories %}
        <div class="filter-group">
            <div class="title">分类</div>
            <div class="content">
                {% set sc_val = request.get('sc','int','all') %}
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
            {% set model_val = request.get('model','trim','all') %}
            {% for model in models %}
                {% set class = model_val == model.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ model.url }}">{{ model.name }}</a>
            {% endfor %}
        </div>
    </div>
    <div class="filter-group">
        <div class="title">难度</div>
        <div class="content">
            {% set level_val = request.get('level','trim','all') %}
            {% for level in levels %}
                {% set class = level_val == level.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ level.url }}">{{ level.name }}</a>
            {% endfor %}
        </div>
    </div>
    <div class="filter-group">
        <div class="title">排序</div>
        <div class="content">
            {% set sort_val = request.get('sort','trim','score') %}
            {% for sort in sorts %}
                {% set class = sort_val == sort.id ? 'layui-btn layui-btn-xs' : 'none' %}
                <a class="{{ class }}" href="{{ sort.url }}">{{ sort.name }}</a>
            {% endfor %}
        </div>
    </div>
</div>

