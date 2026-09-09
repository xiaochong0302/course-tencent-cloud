{{ partial('macros/course') }}

{% if pager.total_items > 0 %}
    <div class="search-course-list">
        {% for item in pager.items %}
            {% set chapter_url = url({'for':'home.chapter.show','id':item.id}) %}
            <div class="search-course-card">
                <div class="left">
                    <div class="model">{{ model_type_badge(item.model) }}</div>
                    <div class="cover">
                        <a href="{{ chapter_url }}" target="_blank">
                            <img src="{{ item.course.cover }}!cover_270" alt="{{ item.title|striptags }}">
                        </a>
                    </div>
                </div>
                <div class="right">
                    <div class="title layui-elip">
                        <a href="{{ chapter_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span>学员：{{ item.user_count|human_number }}</span>
                        <span>点赞：{{ item.like_count|human_number }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
{% else %}
    {{ partial('search/empty') }}
{% endif %}
