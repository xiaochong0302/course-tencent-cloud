<div class="course-widget widget">
    <div class="head">相关课程</div>
    <div class="body">
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