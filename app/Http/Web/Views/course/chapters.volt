{%- macro lesson_info(lesson) %}
    {% if lesson.attrs.model == 'vod' %}
        <li>{{ lesson.title }}</li>
    {% elseif lesson.attrs.model == 'live' %}
        <li>{{ lesson.title }}</li>
    {% elseif lesson.attrs.model == 'read' %}
        <li>{{ lesson.title }}</li>
    {% endif %}
{%- endmacro %}

<div class="layui-collapse">
    {% for chapter in chapters %}
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">{{ chapter.title }}</h2>
            <div class="layui-colla-content layui-show">
                <ul class="lesson-list">
                    {% for lesson in chapter.children %}
                        {{ lesson_info(lesson) }}
                    {% endfor %}
                </ul>
            </div>
        </div>
    {% endfor %}
</div>