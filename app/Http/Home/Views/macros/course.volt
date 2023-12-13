{%- macro model_type(value) %}
    {% if value == 1 %}
        点播
    {% elseif value == 2 %}
        直播
    {% elseif value == 3 %}
        图文
    {% elseif value == 4 %}
        面授
    {% endif %}
{%- endmacro %}

{%- macro level_type(value) %}
    {% if value == 1 %}
        入门
    {% elseif value == 2 %}
        初级
    {% elseif value == 3 %}
        中级
    {% elseif value == 4 %}
        高级
    {% endif %}
{%- endmacro %}

{%- macro star_info(rating) %}
    {% set stars = [1,2,3,4,5] %}
    {% for val in stars if val <= rating %}
        <i class="layui-icon layui-icon-star-fill"></i>
    {% endfor %}
{%- endmacro %}

{%- macro course_card(course) %}
    {% set course_url = url({'for':'home.course.show','id':course.id}) %}
    <div class="course-card">
        <span class="model layui-badge layui-bg-green">{{ model_type(course.model) }}</span>
        <div class="cover">
            <a href="{{ course_url }}" target="_blank">
                <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}" title="{{ course.title }}">
            </a>
        </div>
        <div class="info">
            <div class="title layui-elip">
                <a href="{{ course_url }}" title="{{ course.title }}" target="_blank">{{ course.title }}</a>
            </div>
            <div class="meta">
                {% if course.market_price == 0 %}
                    <span class="free">全员免费</span>
                    <span class="lesson">{{ course.lesson_count }} 节课</span>
                    <span class="user">{{ course.user_count }} 人报名</span>
                {% elseif course.vip_price == 0 %}
                    <span class="free">会员免费</span>
                    <span class="lesson">{{ course.lesson_count }} 节课</span>
                    <span class="user">{{ course.user_count }} 人购买</span>
                {% elseif course.market_price > 0 %}
                    <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
                    <span class="lesson">{{ course.lesson_count }} 节课</span>
                    <span class="user">{{ course.user_count }} 人购买</span>
                {% endif %}
            </div>
        </div>
    </div>
{%- endmacro %}

{%- macro sidebar_course_card(course) %}
    {% set course_url = url({'for':'home.course.show','id':course.id}) %}
    <div class="sidebar-course-card">
        <div class="cover">
            <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
        </div>
        <div class="info">
            <div class="title layui-elip">
                <a href="{{ course_url }}" title="{{ course.title }}" target="_blank">{{ course.title }}</a>
            </div>
            <div class="meta">
                {% if course.market_price == 0 %}
                    <span class="free">全员免费</span>
                    <span class="lesson">{{ course.lesson_count }} 节课</span>
                    <span class="user">{{ course.user_count }} 人报名</span>
                {% elseif course.vip_price == 0 %}
                    <span class="free">会员免费</span>
                    <span class="lesson">{{ course.lesson_count }} 节课</span>
                    <span class="user">{{ course.user_count }} 人购买</span>
                {% elseif course.market_price > 0 %}
                    <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
                    <span class="lesson">{{ course.lesson_count }} 节课</span>
                    <span class="user">{{ course.user_count }} 人购买</span>
                {% endif %}
            </div>
        </div>
    </div>
{%- endmacro %}