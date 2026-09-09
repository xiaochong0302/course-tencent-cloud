{%- macro show_lesson_list(parent,chapter) %}
    <ul class="sidebar-lesson-list">
        {% for lesson in parent.children %}
            {% set url = url({'for':'home.chapter.show','id':lesson.id}) %}
            {% set active = chapter.id == lesson.id ? 'active' : 'normal' %}
            {% set priv = lesson.me.owned == 1 ? 'allow' : 'deny' %}
            <li class="sidebar-lesson {{ priv }} {{ active }}" title="{{ lesson.title }}" data-url="{{ url }}">{{ loop.index }}</li>
        {% endfor %}
    </ul>
{%- endmacro %}

<div class="layui-card sidebar-card sidebar-catalog">
    <div class="layui-card-header">课程目录</div>
    <div class="layui-card-body">
        {% if catalog|length > 1 %}
            <div class="sidebar-chapter-list">
                {% for item in catalog %}
                    <div class="sidebar-chapter layui-elip">{{ item.title }}</div>
                    {{ show_lesson_list(item,chapter) }}
                {% endfor %}
            </div>
        {% else %}
            {{ show_lesson_list(catalog[0],chapter) }}
        {% endif %}
    </div>
</div>
