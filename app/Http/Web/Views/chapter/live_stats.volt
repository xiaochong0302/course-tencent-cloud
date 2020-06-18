<div class="layui-card">
    <div class="layui-card-header">在线成员</div>
    <div class="layui-card-body live-stats">
        <div class="stats">
            用户：<span class="count">{{ stats.user_count }}</span>
            游客：<span class="count">{{ stats.guest_count }}</span>
        </div>
        <div class="live-user-list">
            {% for user in stats.users %}
                {% set vip_flag = user.vip ? '<span class="layui-badge">vip</span>' : '' %}
                <div class="live-user-card">
                    <div class="name">{{ user.name }} {{ vip_flag }}</div>
                </div>
            {% endfor %}
        </div>
    </div>
</div>
