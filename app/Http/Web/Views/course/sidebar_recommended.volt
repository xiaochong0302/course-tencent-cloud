<div class="course-widget layui-card">
    <div class="layui-card-header">推荐课程</div>
    <div class="layui-card-body">
        {% for course in recommended_courses %}
            {% set url = url({'for':'web.course.show','id':course.id}) %}
            <div class="sidebar-course-card clearfix">
                <div class="cover">
                    <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
                </div>
                <div class="info">
                    <div class="title">
                        <a href="{{ url }}" title="{{ course.title }}">{{ substr(course.title,0,15) }}</a>
                    </div>
                    <div class="meta">
                        <span class="price">￥{{ course.market_price }}</span>
                        <span class="level">中级</span>
                        <span class="user">{{ course.user_count }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>