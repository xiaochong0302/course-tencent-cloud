{% set owner_url = url({'for':'home.user.show','id':comment.owner.id}) %}
{% set delete_url = url({'for':'home.comment.delete','id':comment.id}) %}

{% if comment.parent_id == 0 %}
    <div class="comment-box" id="comment-{{ comment.id }}">
        <div class="comment-card">
            <div class="avatar">
                <a href="{{ owner_url }}" title="{{ comment.owner.name }}" target="_blank">
                    <img src="{{ comment.owner.avatar }}!avatar_160" alt="{{ comment.owner.name }}">
                </a>
            </div>
            <div class="info">
                <div class="user">
                    <a href="{{ owner_url }}" target="_blank">{{ comment.owner.name }}</a>
                </div>
                <div class="content">{{ comment.content }}</div>
                <div class="footer">
                    <div class="left">
                        <div class="column">
                            <span class="time">{{ comment.create_time|time_ago }}</span>
                        </div>
                    </div>
                    <div class="right">
                        <div class="column">
                            <span class="action comment-delete" data-id="{{ comment.id }}" data-url="{{ delete_url }}">删除</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endif %}

{% if comment.parent_id > 0 %}
    <div class="comment-card">
        <div class="avatar">
            <a href="{{ owner_url }}" title="{{ comment.owner.name }}" target="_blank">
                <img src="{{ comment.owner.avatar }}!avatar_160" alt="{{ comment.owner.name }}">
            </a>
        </div>
        <div class="info">
            <div class="user">
                <a href="{{ owner_url }}" target="_blank">{{ comment.owner.name }}</a>
                {% if comment.to_user.id is defined %}
                    {% set to_user_url = url({'for':'home.user.show','id':comment.to_user.id}) %}
                    <span class="separator">回复</span>
                    <a class="{{ to_user_url }}" target="_blank">{{ comment.to_user.name }}</a>
                {% endif %}
            </div>
            <div class="content">{{ comment.content }}</div>
            <div class="footer">
                <div class="left">
                    <span class="column">{{ comment.create_time|time_ago }}</span>
                </div>
                <div class="right">
                    <span class="column">
                        <span class="action comment-delete" data-id="{{ comment.id }}" data-url="{{ delete_url }}">删除</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
{% endif %}
