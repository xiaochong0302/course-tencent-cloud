<div class="layui-card sidebar-card sidebar-chapter">
    <div class="layui-card-header">课程目录</div>
    <div class="layui-card-body">
        <div class="sidebar-chapter-list">
            {% for item in contents %}
                <div class="chapter-title layui-elip">{{ item.title }}</div>
                <ul class="sidebar-lesson-list">
                    {% for lesson in item.children %}
                        {% set url = url({'for':'web.chapter.show','id':lesson.id}) %}
                        {% set free_flag = lesson.free == 1 ? '<span class="layui-badge">免费</span>' : '' %}
                        {% set active = (chapter.id == lesson.id) ? 'active' : 'normal' %}
                        <li class="lesson-title layui-elip">
                            {% if lesson.me.owned == 1 %}
                                <a class="{{ active }}" href="{{ url }}" title="{{ lesson.title }}">{{ lesson.title }} {{ free_flag }}</a>
                            {% else %}
                                <span class="deny" title="{{ lesson.title }}">{{ lesson.title }}</span>
                            {% endif %}
                        </li>
                    {% endfor %}
                </ul>
            {% endfor %}
        </div>
    </div>
</div>