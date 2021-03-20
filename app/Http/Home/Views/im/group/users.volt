<div class="user-list group-user-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for item in pager.items %}
            {% set user_url = url({'for':'home.user.show','id':item.id}) %}
            {% set item.title = item.title ? item.title : '暂露头角' %}
            {% set avatar_class = item.vip == 1 ? 'avatar vip' : 'avatar' %}
            <div class="layui-col-md3">
                <div class="user-card">
                    <div class="{{ avatar_class }}">
                        <a href="{{ user_url }}" title="{{ item.about }}">
                            <img src="{{ item.avatar }}" alt="{{ item.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ user_url }}" title="{{ item.about }}">{{ item.name }}</a>
                    </div>
                    <div class="title layui-elip">{{ item.title }}</div>
                    <div class="action">
                        <span class="layui-btn apply-friend" data-id="{{ item.id }}" data-name="{{ item.name }}" data-avatar="{{ item.avatar }}">添加好友</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>
{{ partial('partials/pager_ajax') }}
<br>