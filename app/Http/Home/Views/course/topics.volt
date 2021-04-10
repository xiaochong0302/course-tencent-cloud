{% if topics %}
    <div class="layui-card">
        <div class="layui-card-header">热门专题</div>
        <div class="layui-card-body">
            {% for topic in topics %}
                {% set topic_url = url({'for':'home.topic.show','id':topic.id}) %}
                <a class="layui-badge-rim topic-badge" href="{{ topic_url }}" target="_blank">{{ topic.title }}</a>
            {% endfor %}
        </div>
    </div>
{% endif %}