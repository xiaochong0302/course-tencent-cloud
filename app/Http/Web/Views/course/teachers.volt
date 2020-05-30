<div class="layui-card">
    <div class="layui-card-header">授课教师</div>
    <div class="layui-card-body">
        {% for teacher in teachers %}
            {% set teacher_url = url({'for':'web.user.show','id':teacher.id}) %}
            <div class="sidebar-teacher-card clearfix" title="{{ teacher.about|e }}">
                <div class="avatar">
                    <img src="{{ teacher.avatar }}" alt="{{ teacher.about|e }}">
                </div>
                <div class="info">
                    <div class="name">
                        <a href="{{ teacher_url }}">{{ teacher.name }}</a>
                    </div>
                    {% set title = teacher.title ? teacher.title : '小小教书匠' %}
                    <div class="title">{{ title }}</div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>