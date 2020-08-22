{% if hot_queries %}
    <div class="layui-card">
        <div class="layui-card-header">热门搜索</div>
        <div class="layui-card-body">
            {% for query in hot_queries %}
                {% set url = url({'for':'desktop.search.index'},{'type':type,'query':query}) %}
                <a class="layui-badge-rim query-badge" href="{{ url }}">{{ query }}</a>
            {% endfor %}
        </div>
    </div>
{% endif %}

{% if related_queries %}
    <div class="layui-card">
        <div class="layui-card-header">相关搜索</div>
        <div class="layui-card-body">
            {% for query in related_queries %}
                {% set url = url({'for':'desktop.search.index'},{'type':type,'query':query}) %}
                <a class="layui-badge-rim query-badge" href="{{ url }}">{{ query }}</a>
            {% endfor %}
        </div>
    </div>
{% endif %}