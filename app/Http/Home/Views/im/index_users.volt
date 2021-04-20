<div class="user-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for user in users %}
            {% set user_url = url({'for':'home.user.show','id':user.id}) %}
            {% set avatar_class = user.vip == 1 ? 'avatar vip' : 'avatar' %}
            <div class="layui-col-md3">
                <div class="user-card">
                    <div class="{{ avatar_class }}">
                        <a href="{{ user_url }}" title="{{ user.about }}" target="_blank">
                            <img src="{{ user.avatar }}!avatar_160" alt="{{ user.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ user_url }}" title="{{ user.about }}" target="_blank">{{ user.name }}</a>
                    </div>
                    <div class="title layui-elip">{{ user.title|default('暂露头角') }}</div>
                    <div class="action">
                        <span class="layui-btn apply-friend" data-id="{{ user.id }}" data-name="{{ user.name }}" data-avatar="{{ user.avatar }}">添加好友</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>