<div class="teacher-widget layui-card">
    <div class="layui-card-header">授课教师</div>
    <div class="layui-card-body">
        {% for teacher in teachers %}
            <div class="teacher" title="{{ teacher.about }}">
                <div class="avatar"></div>
                <div class="info">
                    <div class="name">{{ teacher.name }}</div>
                    <div class="title">{{ teacher.title }}</div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>