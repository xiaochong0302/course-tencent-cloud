{{ partial('macros/course') }}

{% if pager.total_pages > 0 %}
    <div class="course-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                <div class="layui-col-md3">
                    {{ learning_course_card(item) }}
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}