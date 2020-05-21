<div class="course-widget layui-card">
    <div class="layui-card-header">相关课程</div>
    <div class="layui-card-body">
        {% for course in courses %}
            <div class="course">
                <div class="cover"></div>
                <div class="info">
                    <div class="title">{{ course.title }}</div>
                    <div class="meta">
                        <span class="price"></span>
                        <span class="level"></span>
                        <span class="user"></span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>