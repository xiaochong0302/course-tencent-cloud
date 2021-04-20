{% if users %}
    <div class="layui-card">
        <div class="layui-card-header">活跃成员</div>
        <div class="layui-card-body">
            {% for user in users %}
                {% set user_url = url({'for':'home.user.show','id':user.id}) %}
                <div class="sidebar-user-card clearfix">
                    <div class="avatar">
                        <img src="{{ user.avatar }}" alt="{{ user.name }}">
                    </div>
                    <div class="info">
                        <div class="name layui-elip">
                            <a href="{{ user_url }}" title="{{ user.about }}" target="_blank">{{ user.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ user.title|default('暂露头角') }}</div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
{% endif %}