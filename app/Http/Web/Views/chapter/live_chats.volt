{% for chat in chats %}
    <div class="chat">
        {% if chat.user.vip == 0 %}
            <span class="vip-icon layui-icon layui-icon-diamond"></span>
        {% endif %}
        <span class="user">{{ chat.user.name }}</span>
        <span class="content">{{ chat.content }}</span>
    </div>
{% endfor %}