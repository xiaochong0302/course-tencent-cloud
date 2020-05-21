<div class="topic-widget layui-card">
    <div class="layui-card-header">热门专题</div>
    <div class="layui-card-body">
        {% for topic in topics %}
            <a class="layui-badge" href="{{ url({'for':'web.topic.show','id':topic.id}) }}">{{ topic.title }}</a>
        {% endfor %}
    </div>
</div>