{% set teacher_url = url({'for':'home.teacher.show','id':course.teacher.id}) %}
<div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">授课教师</div>
            <div class="layui-card-body">
                <div class="sidebar-user-card">
                    <div class="avatar">
                        <img src="{{ course.teacher.avatar }}!avatar_160" alt="{{ course.teacher.name }}">
                    </div>
                    <div class="info">
                        <div class="name layui-elip">
                            <a href="{{ teacher_url }}" title="{{ course.teacher.about }}" target="_blank">{{ course.teacher.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ course.teacher.title|default('小小教书匠') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
