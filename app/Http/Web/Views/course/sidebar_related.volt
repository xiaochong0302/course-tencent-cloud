<div class="layui-card">
    <div class="layui-card-header">相关课程</div>
    <div class="layui-card-body">
        {% for course in related_courses %}
            {{ sidebar_course_card(course) }}
        {% endfor %}
    </div>
</div>