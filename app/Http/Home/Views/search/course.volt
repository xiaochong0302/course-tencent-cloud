{% if pager.total_pages > 0 %}
    <div class="search-course-list">
        {% for item in pager.items %}
            {% set course_url = url({'for':'home.course.show','id':item.id}) %}
            {% set teacher_url = url({'for':'home.teacher.show','id':item.teacher.id}) %}
            <div class="search-course-card">
                <div class="cover">
                    <a href="{{ course_url }}" target="_blank">
                        <img src="{{ item.cover }}!cover_270" alt="{{ item.title|striptags }}">
                    </a>
                </div>
                <div class="info">
                    <div class="title layui-elip">
                        <a href="{{ course_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span>讲师：<a href="{{ teacher_url }}" target="_blank">{{ item.teacher.name }}</a></span>
                        <span>难度：{{ level_type(item.level) }}</span>
                        <span>课时：{{ item.lesson_count }}</span>
                        <span>学员：{{ item.user_count }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
{% else %}
    {{ partial('search/empty') }}
{% endif %}
