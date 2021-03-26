{%- macro vod_lesson_info(lesson) %}
    {% set url = lesson.me.owned ? url({'for':'home.chapter.show','id':lesson.id}) : '' %}
    {% set priv = lesson.me.owned ? 'allow' : 'deny' %}
    <a class="{{ priv }} view-lesson" href="javascript:" data-url="{{ url }}">
        <i class="layui-icon layui-icon-play"></i>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.free == 1 %}
            <span class="layui-badge free-badge">免费</span>
        {% endif %}
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
        <span class="duration">{{ lesson.attrs.duration|duration }}</span>
    </a>
{%- endmacro %}

{%- macro live_lesson_info(lesson) %}
    {% set url = lesson.me.owned ? url({'for':'home.chapter.show','id':lesson.id}) : '' %}
    {% set priv = lesson.me.owned ? 'allow' : 'deny' %}
    <a class="{{ priv }} view-lesson" href="javascript:" data-url="{{ url }}">
        <i class="layui-icon layui-icon-video"></i>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.free == 1 %}
            <span class="layui-badge free-badge">免费</span>
        {% endif %}
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
        <span class="live" title="{{ date('Y-m-d H:i',lesson.attrs.start_time) }}">{{ live_status_info(lesson) }}</span>
    </a>
{%- endmacro %}

{%- macro read_lesson_info(lesson) %}
    {% set url = lesson.me.owned ? url({'for':'home.chapter.show','id':lesson.id}) : '' %}
    {% set priv = lesson.me.owned ? 'allow' : 'deny' %}
    <a class="{{ priv }} view-lesson" href="javascript:" data-url="{{ url }}">
        <i class="layui-icon layui-icon-read"></i>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.free == 1 %}
            <span class="layui-badge free-badge">免费</span>
        {% endif %}
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
    </a>
{%- endmacro %}

{%- macro offline_lesson_info(lesson) %}
    <a class="deny view-lesson" href="javascript:">
        <i class="layui-icon layui-icon-user"></i>
        <span class="title">{{ lesson.title }}</span>
        {% if lesson.free == 1 %}
            <span class="layui-badge free-badge">免费</span>
        {% endif %}
        <span class="live" title="{{ date('Y-m-d H:i',lesson.attrs.start_time) }}">{{ offline_status_info(lesson) }}</span>
    </a>
{%- endmacro %}

{%- macro live_status_info(lesson) %}
    {% if lesson.attrs.stream.status == 'active' %}
        <span class="active">{{ date('m月d日 H:i',lesson.attrs.start_time) }} 直播中</span>
    {% elseif lesson.attrs.start_time > time() %}
        <span class="pending">{{ date('m月d日 H:i',lesson.attrs.start_time) }} 倒计时</span>
    {% elseif lesson.attrs.end_time < time() %}
        <span class="finished">{{ date('m月d日 H:i',lesson.attrs.start_time) }} 已结束</span>
    {% endif %}
{%- endmacro %}

{%- macro offline_status_info(lesson) %}
    {% if lesson.attrs.start_time < time() and lesson.attrs.end_time > time() %}
        <span class="active">{{ date('m月d日 H:i',lesson.attrs.start_time) }} 授课中</span>
    {% elseif lesson.attrs.start_time > time() %}
        <span class="pending">{{ date('m月d日 H:i',lesson.attrs.start_time) }} 未开始</span>
    {% elseif lesson.attrs.end_time < time() %}
        <span class="finished">{{ date('m月d日 H:i',lesson.attrs.start_time) }} 已结束</span>
    {% endif %}
{%- endmacro %}

{% if chapters %}
    <div class="layui-collapse">
        {% for chapter in chapters %}
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">{{ chapter.title }}</h2>
                <div class="layui-colla-content layui-show">
                    <ul class="lesson-list">
                        {% for lesson in chapter.children %}
                            {% if lesson.model == 1 %}
                                <li class="lesson-item clearfix">{{ vod_lesson_info(lesson) }}</li>
                            {% elseif lesson.model == 2 %}
                                <li class="lesson-item clearfix">{{ live_lesson_info(lesson) }}</li>
                            {% elseif lesson.model == 3 %}
                                <li class="lesson-item clearfix">{{ read_lesson_info(lesson) }}</li>
                            {% elseif lesson.model == 4 %}
                                <li class="lesson-item clearfix">{{ offline_lesson_info(lesson) }}</li>
                            {% endif %}
                        {% endfor %}
                    </ul>
                </div>
            </div>
        {% endfor %}
    </div>
{% endif %}