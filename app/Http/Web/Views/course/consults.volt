{% if pager.total_pages > 0 %}
    <div class="review-list">
        {% for item in pager.items %}
            {% set user_url = url({'for':'web.user.show','id':item.id}) %}
            <div class="review-card clearfix">
                <div class="avatar">
                    <a href="{{ user_url }}">
                        <img src="{{ item.user.avatar }}" alt="{{ item.user.name }}" title="{{ item.user.name }}">
                    </a>
                </div>
                <div class="info">
                    <div class="title">{{ item.question }}</div>
                    <div class="content">{{ item.answer }}</div>
                    <div class="footer">
                        <span>{{ date('Y-m-d H:i',item.create_time) }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}