{%- macro model_info(value) %}
    {% if value == '1' %}
        <span class="layui-badge layui-bg-green">点播</span>
    {% elseif value == '2' %}
        <span class="layui-badge layui-bg-blue">直播</span>
    {% elseif value == '3' %}
        <span class="layui-badge layui-bg-black">专栏</span>
    {% endif %}
{%- endmacro %}

{%- macro level_info(value) %}
    {% if value == '1' %}
        入门
    {% elseif value == '2' %}
        初级
    {% elseif value == '3' %}
        中级
    {% elseif value == '4' %}
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
        <div class="cover">
            <a href="{{ course_url }}">
                <img src="{{ course.cover }}!cover_270" alt="{{ course.title|e }}" title="{{ course.title|e }}">
            </a>
        </div>
        <div class="info">
            <div class="title layui-elip">
                <a href="{{ course_url }}" title="{{ course.title|e }}">{{ course.title }}</a>
            </div>
            <div class="meta">
                {% if course.market_price > course.vip_price %}
                    <span>{{ '￥%0.2f'|format(course.market_price) }}</span>
                    {% if course.vip_price > 0 %}
                        <span class="price">{{ '会员￥%0.2f'|format(course.vip_price) }}</span>
                    {% else %}
                        <span class="free">会员免费</span>
                    {% endif %}
                    <span class="level">{{ level_info(course.level) }}</span>
                    <span class="user">{{ course.user_count }}人购买</span>
                {% elseif course.market_price > 0 %}
                    <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
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
    </div>
{%- endmacro %}

{%- macro sidebar_course_card(course) %}
    {% set course_url = url({'for':'home.course.show','id':course.id}) %}
    <div class="sidebar-course-card clearfix">
        <div class="cover">
            <img src="{{ course.cover }}!cover_270" alt="{{ course.title|e }}">
        </div>
        <div class="info">
            <div class="title layui-elip">
                <a href="{{ course_url }}" title="{{ course.title|e }}">{{ course.title }}</a>
            </div>
            <div class="meta">
                {% if course.market_price > 0 %}
                    <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
                    <span class="level">{{ level_info(course.level) }}</span>
                    <span class="user">{{ course.user_count }}人购买</span>
                {% else %}
                    <span class="free">免费</span>
                    <span class="level">{{ level_info(course.level) }}</span>
                    <span class="user">{{ course.user_count }}人报名</span>
                {% endif %}
            </div>
        </div>
    </div>
{%- endmacro %}

{%- macro learning_course_card(item) %}
    {% set course_title = item.course.title|e %}
    {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
    <div class="course-card">
        <div class="cover">
            <a href="{{ course_url }}" title="{{ course_title }}">
                <img src="{{ item.course.cover }}!cover_270" alt="{{ course_title }}">
            </a>
        </div>
        <div class="info">
            <div class="title layui-elip">
                <a href="{{ course_url }}" title="{{ course_title }}">{{ course_title }}</a>
            </div>
            <div class="meta">
                <span>已学习 {{ item.duration|duration }}</span>
                <span>已完成 {{ item.progress }}%</span>
            </div>
        </div>
    </div>
{%- endmacro %}