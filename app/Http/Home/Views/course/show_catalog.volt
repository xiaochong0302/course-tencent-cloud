{%- macro show_lesson_list(chapter) %}
    <ul class="lesson-list">
        {% for lesson in chapter.children %}
            {% set url = url({'for':'home.chapter.show','id':lesson.id}) %}
            {% set priv = lesson.me.owned ? 'allow' : 'deny' %}
            {% if lesson.model == 1 %}
                <li class="lesson-item {{ priv }}" data-url="{{ url }}">{{ vod_lesson_info(lesson) }}</li>
            {% elseif lesson.model == 2 %}
                <li class="lesson-item {{ priv }}" data-url="{{ url }}">{{ live_lesson_info(lesson) }}</li>
            {% elseif lesson.model == 3 %}
                <li class="lesson-item {{ priv }}" data-url="{{ url }}">{{ read_lesson_info(lesson) }}</li>
            {% elseif lesson.model == 4 %}
                <li class="lesson-item deny" data-url="{{ url }}">{{ offline_lesson_info(lesson) }}</li>
            {% endif %}
        {% endfor %}
    </ul>
{%- endmacro %}

{%- macro vod_lesson_info(lesson) %}
    <div class="left">
        <span class="model"><i class="iconfont icon-video"></i></span>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
        {% if lesson.me.owned == 0 %}
            <span class="lock"><i class="iconfont icon-lock"></i></span>
        {% endif %}
        {% if lesson.free == 1 %}
            <span class="flag flag-free">试听</span>
        {% endif %}
    </div>
    <div class="right">
        <span class="duration">{{ lesson.attrs.duration|duration }}</span>
    </div>
{%- endmacro %}

{%- macro live_lesson_info(lesson) %}
    <div class="left">
        <span class="model"><i class="iconfont icon-live"></i></span>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
        {% if lesson.me.owned == 0 %}
            <span class="lock"><i class="iconfont icon-lock"></i></span>
        {% endif %}
        {% if lesson.attrs.playback.ready == 1 %}
            <span class="flag flag-playback">回放</span>
        {% endif %}
        {% if lesson.free == 1 %}
            <span class="flag flag-free">试听</span>
        {% endif %}
    </div>
    <div class="right">
        <span class="live-status">{{ live_status_info(lesson) }}</span>
        <span class="live-time">{{ date('Y-m-d H:i',lesson.attrs.start_time) }}</span>
    </div>
{%- endmacro %}

{%- macro read_lesson_info(lesson) %}
    <div class="left">
        <span class="model"><i class="iconfont icon-article"></i></span>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
        {% if lesson.me.owned == 0 %}
            <span class="lock"><i class="iconfont icon-lock"></i></span>
        {% endif %}
        {% if lesson.free == 1 %}
            <span class="flag flag-free">试读</span>
        {% endif %}
    </div>
    <div class="right">
        <span class="size"></span>
    </div>
{%- endmacro %}

{%- macro offline_lesson_info(lesson) %}
    <div class="left">
        <span class="model"><i class="layui-icon layui-icon-user"></i></span>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.me.owned == 0 %}
            <span class="lock"><i class="iconfont icon-lock"></i></span>
        {% endif %}
        {% if lesson.free == 1 %}
            <span class="flag flag-free">试听</span>
        {% endif %}
    </div>
    <div class="right">
        <span class="live-status">{{ offline_status_info(lesson) }}</span>
        <span class="live-time">{{ date('Y-m-d H:i',lesson.attrs.start_time) }}</span>
    </div>
{%- endmacro %}

{%- macro live_status_info(lesson) %}
    {% if lesson.attrs.stream.status == 'active' %}
        <span class="flag flag-active">直播中</span>
    {% elseif lesson.attrs.start_time > time() %}
        <span class="flag flag-scheduled">倒计时</span>
    {% elseif lesson.attrs.end_time < time() %}
        <span class="flag flag-ended">已结束</span>
    {% elseif lesson.attrs.stream.status == 'inactive' %}
        <span class="flag flag-inactive">未推流</span>
    {% endif %}
{%- endmacro %}

{%- macro offline_status_info(lesson) %}
    {% if lesson.attrs.start_time < time() and lesson.attrs.end_time > time() %}
        <span class="flag flag-active">授课中</span>
    {% elseif lesson.attrs.start_time > time() %}
        <span class="flag flag-scheduled">未开始</span>
    {% elseif lesson.attrs.end_time < time() %}
        <span class="flag flag-ended">已结束</span>
    {% endif %}
{%- endmacro %}

{% set show_all = course.lesson_count < 30 %}

{% if chapters|length > 0 %}
    {% if chapters|length > 1 %}
        <div class="layui-collapse" lay-accordion="true">
            {% for chapter in chapters %}
                {% set show_class = (show_all or loop.first) ? 'layui-show' : '' %}
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title">{{ chapter.title }}</h2>
                    <div class="layui-colla-content {{ show_class }}">
                        {{ show_lesson_list(chapter) }}
                    </div>
                </div>
            {% endfor %}
        </div>
    {% else %}
        {{ show_lesson_list(chapters[0]) }}
    {% endif %}
{% endif %}
