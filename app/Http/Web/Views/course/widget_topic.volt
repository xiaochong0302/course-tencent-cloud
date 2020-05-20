<div class="teacher-widget widget">
    <div class="head">热门专题</div>
    <div class="body">
        {% for topic in topics %}
            <a class="layui-badge" href="{{ url({'for':'web.topic.show','id':topic.id}) }}">{{ topic.title }}</a>
        {% endfor %}
    </div>
</div>