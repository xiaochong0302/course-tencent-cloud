{% if related_queries %}
    <div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">相关搜索</div>
            <div class="layui-card-body">
                {% for query in related_queries %}
                    {% set url = url({'for':'home.search.index'},{'type':type,'query':query}) %}
                    <a class="layui-badge-rim query-badge" href="{{ url }}">{{ query }}</a>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}

{% if type == 'course' %}
    {% set load_url = url({'for':'home.widget.featured_courses'}) %}
    <div class="sidebar" id="sidebar-course-list" data-url="{{ load_url }}"></div>
{% elseif type == 'chapter' %}
    {% set load_url = url({'for':'home.widget.featured_courses'}) %}
    <div class="sidebar" id="sidebar-course-list" data-url="{{ load_url }}"></div>
{% endif %}

{% if hot_queries %}
    <div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">热门搜索</div>
            <div class="layui-card-body">
                {% for query in hot_queries %}
                    {% set url = url({'for':'home.search.index'},{'type':type,'query':query}) %}
                    <a class="layui-badge-rim query-badge" href="{{ url }}">{{ query }}</a>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}
