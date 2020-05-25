{%- macro lesson_info(lesson) %}

    {% set url = lesson.me.owned ? url({'for':'web.chapter.show','id':lesson.id}) : 'javascript:' %}
    {% set free_badge = lesson.free ? '<span class="layui-badge">免费</span>' : '' %}

    {% if lesson.attrs.model == 'vod' %}
        <a href="{{ url }}"><i class="layui-icon layui-icon-play"></i> {{ lesson.title }} {{ free_badge }}</a>
    {% elseif lesson.attrs.model == 'live' %}
        <a href="{{ url }}"><i class="layui-icon layui-icon-video"></i> {{ lesson.title }} {{ free_badge }}</a>
    {% elseif lesson.attrs.model == 'read' %}
        <a href="{{ url }}"><i class="layui-icon layui-icon-note"></i> {{ lesson.title }} {{ free_badge }}</a>
    {% endif %}
{%- endmacro %}

<div class="layui-collapse">
    {% for chapter in chapters %}
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">{{ chapter.title }}</h2>
            <div class="layui-colla-content layui-show">
                <ul class="lesson-list">
                    {% for lesson in chapter.children %}
                        <li>{{ lesson_info(lesson) }}</li>
                    {% endfor %}
                </ul>
            </div>
        </div>
    {% endfor %}
</div>