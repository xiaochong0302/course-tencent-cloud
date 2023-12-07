{% if course.teacher %}
    {% set teacher = course.teacher %}
    <div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">授课教师</div>
            <div class="layui-card-body">
                {% set teacher_url = url({'for':'home.teacher.show','id':teacher.id}) %}
                <div class="sidebar-user-card">
                    <div class="avatar">
                        <img src="{{ teacher.avatar }}!avatar_160" alt="{{ teacher.name }}">
                    </div>
                    <div class="info">
                        <div class="name layui-elip">
                            <a href="{{ teacher_url }}" title="{{ teacher.about }}" target="_blank">{{ teacher.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ teacher.title|default('小小教书匠') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endif %}