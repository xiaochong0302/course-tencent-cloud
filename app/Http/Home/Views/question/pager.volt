{{ partial('macros/question') }}

{% if pager.total_pages > 0 %}
    <div class="question-list">
        {% for item in pager.items %}
            {% set question_url = url({'for':'home.question.show','id':item.id}) %}
            {% set solved_class = item.solved ? 'column solved' : 'column' %}
            <div class="article-card question-card">
                <div class="info">
                    <div class="title layui-elip">
                        <a href="{{ question_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        {% if item.last_replier.id is defined %}
                            {% set last_replier_url = url({'for':'home.user.show','id':item.last_replier.id}) %}
                            <span class="replier"><a href="{{ last_replier_url }}">{{ item.last_replier.name }}</a></span>
                            <span class="time">{{ item.last_reply_time|time_ago }}</span>
                            <span class="view">{{ item.view_count }} 浏览</span>
                            <span class="like">{{ item.like_count }} 点赞</span>
                            <span class="answer">{{ item.answer_count }} 回答</span>
                        {% else %}
                            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
                            <span class="owner"><a href="{{ owner_url }}">{{ item.owner.name }}</a></span>
                            <span class="time">{{ item.create_time|time_ago }}</span>
                            <span class="view">{{ item.view_count }} 浏览</span>
                            <span class="like">{{ item.like_count }} 点赞</span>
                            <span class="answer">{{ item.answer_count }} 回答</span>
                        {% endif %}
                    </div>
                </div>
                {% if item.cover %}
                    <div class="cover">
                        <a href="{{ question_url }}" target="_blank">
                            <img src="{{ item.cover }}!cover_270" alt="{{ item.title }}">
                        </a>
                    </div>
                {% endif %}
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
