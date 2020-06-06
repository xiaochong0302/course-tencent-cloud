{{ partial('partials/macro_course') }}

{% if pager.total_pages > 0 %}
    <div class="course-list clearfix">
        {% for item in pager.items %}
            {{ learning_course_card(item) }}
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}