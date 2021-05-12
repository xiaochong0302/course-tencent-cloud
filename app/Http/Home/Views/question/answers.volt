{% if pager.total_pages > 0 %}
    {% for item in pager.items %}
        {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
        {% set like_url = url({'for':'home.answer.like','id':item.id}) %}
        {% set report_url = url({'for':'home.answer.report','id':item.id}) %}
        {% set edit_url = url({'for':'home.answer.edit','id':item.id}) %}
        {% set delete_url = url({'for':'home.answer.delete','id':item.id}) %}
        <div class="comment-card answer-card" id="answer-{{ item.id }}">
            <div class="avatar">
                <a href="{{ owner_url }}" title="{{ item.owner.name }}" target="_blank">
                    <img src="{{ item.owner.avatar }}!avatar_160" alt="{{ item.owner.name }}">
                </a>
            </div>
            <div class="info">
                <div class="user">
                    <a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a>
                </div>
                <div class="content markdown-body">{{ item.content }}</div>
                <div class="footer">
                    <div class="left">
                        <div class="column">
                            <span class="time" title="{{ date('Y-m-d H:i',item.create_time) }}">{{ item.create_time|time_ago }}</span>
                        </div>
                        <div class="column">
                            <span class="like-count" data-count="{{ item.like_count }}">{{ item.like_count }}</span>
                            {% if item.me.liked == 1 %}
                                <span class="action answer-like liked" title="取消点赞" data-url="{{ like_url }}">已赞</span>
                            {% else %}
                                <span class="action answer-like" title="点赞支持" data-url="{{ like_url }}">点赞</span>
                            {% endif %}
                        </div>
                    </div>
                    <div class="right">
                        <div class="column">
                            <span class="action answer-report" data-url="{{ report_url }}">举报</span>
                        </div>
                        {% if auth_user.id == item.owner.id %}
                            <div class="column">
                                <span class="action answer-edit" data-url="{{ edit_url }}">编辑</span>
                            </div>
                            <div class="column">
                                <span class="action kg-delete" data-url="{{ delete_url }}">删除</span>
                            </div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    {% endfor %}
    {{ partial('partials/pager_ajax') }}
{% endif %}