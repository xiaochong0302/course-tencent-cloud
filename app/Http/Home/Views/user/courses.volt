{{ partial('macros/course') }}

{% if pager.total_pages > 0 %}
    <div class="course-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                <div class="layui-col-md3">
                    {% set course_title = item.course.title %}
                    {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
                    <div class="course-card">
                        <span class="model layui-badge layui-bg-green">{{ model_type(item.course.model) }}</span>
                        <div class="cover">
                            <a href="{{ course_url }}" title="{{ course_title }}" target="_blank">
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
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}