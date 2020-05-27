<div class="course-widget layui-card">
    <div class="layui-card-header">推荐课程</div>
    <div class="layui-card-body">
        {% for course in recommended_courses %}
            {{ sidebar_course_card(course) }}
        {% endfor %}
    </div>
</div>