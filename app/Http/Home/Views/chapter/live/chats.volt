{% for chat in chats %}
    {% if chat.user.vip == 1 %}
        <div class="chat chat-vip">
            <span class="icon"><i class="layui-icon layui-icon-diamond"></i></span>
            <span class="user layui-badge layui-bg-orange">{{ chat.user.name }}</span>
            <span class="content">{{ chat.content }}</span>
        </div>
    {% else %}
        <div class="chat chat-normal">
            <span class="icon"><i class="layui-icon layui-icon-username"></i></span>
            <span class="user layui-badge layui-bg-blue">{{ chat.user.name }}</span>
            <span class="content">{{ chat.content }}</span>
        </div>
    {% endif %}
{% endfor %}
