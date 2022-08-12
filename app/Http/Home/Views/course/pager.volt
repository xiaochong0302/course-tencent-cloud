{{ partial('macros/course') }}

{% if pager.total_pages > 0 %}
    <div class="course-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                <div class="layui-col-md3">
                    {{ course_card(item) }}
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
