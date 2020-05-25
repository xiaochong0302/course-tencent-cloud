<div class="layui-card">
    <div class="layui-card-header">授课教师</div>
    <div class="layui-card-body">
        {% for teacher in teachers %}
            <div class="sidebar-teacher-card clearfix">
                <div class="avatar">
                    <img src="{{ teacher.avatar }}" alt="{{ teacher.about }}">
                </div>
                <div class="info">
                    <div class="name">
                        <a href="{{ url({'for':'web.user.show','id':teacher.id}) }}">{{ teacher.name }}</a>
                    </div>
                    <div class="title">{{ teacher.title }}</div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>