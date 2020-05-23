{% if pager.total_pages > 0 %}
    <div class="search-course-list">
        {% for item in pager.items %}
            <div class="search-course-card clearfix">
                <div class="cover">
                    <a href="{{ url({'for':'web.course.show','id':item.id}) }}">
                        <img src="{{ item.cover }}!cover_270" alt="{{ item.title|striptags }}">
                    </a>
                </div>
                <div class="info">
                    <div class="title">
                        <a href="{{ url({'for':'web.course.show','id':item.id}) }}">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span>分类：{{ item.category.name }}</span>
                        <span>讲师：{{ item.teacher.name }}</span>
                        <span>难度：{{ level_info(item.level) }}</span>
                        <span>课时：{{ item.lesson_count }}</span>
                        <span>学员：{{ item.user_count }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
{% else %}
    <div class="search-empty">
        <div class="icon">
            <i class="layui-icon layui-icon-face-surprised"></i>
        </div>
        <div class="text">
            没有找到<span class="query">{{ request.get('query') }}</span>相关内容，换个关键字试试吧！
        </div>
    </div>
{% endif %}