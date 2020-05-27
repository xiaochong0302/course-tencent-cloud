{%- macro read_lesson_info(lesson) %}

    {% set url = lesson.me.owned ? url({'for':'web.chapter.show','id':lesson.id}) : 'javascript:' %}

    <a href="{{ url }}">
        <i class="layui-icon layui-icon-read"></i>
        <span class="title">{{ lesson.title|e }}</span>
        {% if lesson.free == 1 %}
            <span class="layui-badge free-badge">免费</span>
        {% endif %}
        {% if lesson.me.duration > 0 %}
            <span class="study-time" title="学习时长：{{ lesson.me.duration|total_duration }}"><i class="layui-icon layui-icon-time"></i></span>
        {% endif %}
    </a>

{%- endmacro %}

<div class="layui-collapse">
    {% for chapter in chapters %}
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">{{ chapter.title|e }}</h2>
            <div class="layui-colla-content layui-show">
                <ul class="lesson-list">
                    {% for lesson in chapter.children %}
                        <li class="lesson-item">{{ read_lesson_info(lesson) }}</li>
                    {% endfor %}
                </ul>
            </div>
        </div>
    {% endfor %}
</div>