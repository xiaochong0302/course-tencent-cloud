{% if course.teachers %}
    <div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">授课教师</div>
            <div class="layui-card-body">
                {% for teacher in course.teachers %}
                    {% set teacher_url = url({'for':'home.user.show','id':teacher.id}) %}
                    <div class="sidebar-user-card clearfix">
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
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}