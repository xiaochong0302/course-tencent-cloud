<div class="user-list group-user-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for item in pager.items %}
            {% set user_url = url({'for':'home.user.show','id':item.user.id}) %}
            {% set avatar_class = item.user.vip == 1 ? 'avatar vip' : 'avatar' %}
            <div class="layui-col-md3">
                <div class="user-card">
                    <div class="{{ avatar_class }}">
                        <a href="{{ user_url }}" title="{{ item.user.about }}" target="_blank">
                            <img src="{{ item.user.avatar }}" alt="{{ item.user.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ user_url }}" title="{{ item.user.about }}" target="_blank">{{ item.user.name }}</a>
                    </div>
                    <div class="title layui-elip">{{ item.user.title|default('暂露头角') }}</div>
                    <div class="action">
                        <span class="layui-btn apply-friend" data-id="{{ item.user.id }}" data-name="{{ item.user.name }}" data-avatar="{{ item.user.avatar }}">添加好友</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>
{{ partial('partials/pager_ajax') }}
<br>