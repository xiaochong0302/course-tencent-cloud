{%- macro model_info(value) %}
    {% if value == 'vod' %}
        <span class="layui-badge layui-bg-green">点播</span>
    {% elseif value == 'live' %}
        <span class="layui-badge layui-bg-blue">直播</span>
    {% elseif value == 'read' %}
        <span class="layui-badge layui-bg-black">图文</span>
    {% endif %}
{%- endmacro %}

{%- macro level_info(value) %}
    {% if value == 'entry' %}
        入门
    {% elseif value == 'junior' %}
        初级
    {% elseif value == 'medium' %}
        中级
    {% elseif value == 'senior' %}
        高级
    {% endif %}
{%- endmacro %}

{%- macro course_card(course) %}
    <div class="course-card">
        <div class="cover">
            <a href="{{ url({'for':'web.course.show','id':course.id}) }}">
                <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
            </a>
        </div>
        <div class="title">
            <a href="{{ url({'for':'web.course.show','id':course.id}) }}">{{ substr(course.title,0,15) }}</a>
        </div>
        <div class="meta">
            {% if course.market_price > 0 %}
                <span class="price">￥{{ course.market_price }}</span>
                <span class="level">{{ level_info(course.level) }}</span>
                <span class="lesson">{{ course.lesson_count }}节课</span>
                <span class="user">{{ course.user_count }}人购买</span>
            {% else %}
                <span class="free">免费</span>
                <span class="level">{{ level_info(course.level) }}</span>
                <span class="lesson">{{ course.lesson_count }}节课</span>
                <span class="user">{{ course.user_count }}人报名</span>
            {% endif %}
        </div>
    </div>
{%- endmacro %}