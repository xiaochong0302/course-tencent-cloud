<div class="sidebar-chapter-wrap wrap">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>课程目录</legend>
        <div class="layui-field-box">
            <div class="sidebar-chapter-list">
                {% for item in chapters %}
                    <div class="chapter-title layui-elip">{{ item.title }}</div>
                    <ul class="sidebar-lesson-list">
                        {% for lesson in item.children %}
                            {% set url = url({'for':'web.chapter.show','id':lesson.id}) %}
                            {% set active = (chapter.id == lesson.id) ? 'active' : 'normal' %}
                            <li class="lesson-title layui-elip">
                                {% if lesson.me.owned == 1 %}
                                    <a class="{{ active }}" href="{{ url }}" title="{{ lesson.title|e }}">{{ lesson.title }}</a>
                                {% else %}
                                    <span class="deny" title="{{ lesson.title|e }}">{{ lesson.title }}</span>
                                {% endif %}
                            </li>
                        {% endfor %}
                    </ul>
                {% endfor %}
            </div>
        </div>
    </fieldset>
</div>