{%- macro show_lesson_list(parent,chapter) %}
    <ul class="sidebar-lesson-list">
        {% for lesson in parent.children %}
            {% set url = url({'for':'home.chapter.show','id':lesson.id}) %}
            {% set active = (chapter.id == lesson.id) ? 'active' : 'normal' %}
            <li class="lesson-title layui-elip">
                {% if lesson.me.owned == 1 %}
                    <a class="{{ active }}" href="{{ url }}" title="{{ lesson.title }}">{{ lesson.title }}</a>
                {% else %}
                    <span class="deny" title="{{ lesson.title }}">{{ lesson.title }}</span>
                {% endif %}
            </li>
        {% endfor %}
    </ul>
{%- endmacro %}

<div class="layui-card sidebar-card sidebar-chapter">
    <div class="layui-card-header">课程目录</div>
    <div class="layui-card-body">
        {% if catalog|length > 1 %}
            <div class="sidebar-chapter-list">
                {% for item in catalog %}
                    <div class="chapter-title layui-elip">{{ item.title }}</div>
                    <div class="sidebar-lesson-list">
                        {{ show_lesson_list(item,chapter) }}
                    </div>
                {% endfor %}
            </div>
        {% else %}
            <div class="sidebar-lesson-list">
                {{ show_lesson_list(catalog[0],chapter) }}
            </div>
        {% endif %}
    </div>
</div>