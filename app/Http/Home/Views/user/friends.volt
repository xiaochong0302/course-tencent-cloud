{{ partial('macros/user') }}

{% if pager.total_pages > 0 %}
    <div class="user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set user_url = url({'for':'home.user.show','id':item.id}) %}
                {% set avatar_class = item.vip == 1 ? 'avatar vip' : 'avatar' %}
                <div class="layui-col-md2">
                    <div class="user-card">
                        <div class="{{ avatar_class }}">
                            <a href="{{ user_url }}" title="{{ item.about }}" target="_blank">
                                <img src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                            </a>
                        </div>
                        <div class="name layui-elip">
                            <a href="{{ user_url }}" title="{{ item.about }}" target="_blank">{{ item.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ item.title|default('暂露头角') }}</div>
                        <div class="action">
                            <span class="layui-btn apply-friend" data-id="{{ item.id }}" data-name="{{ item.name }}" data-avatar="{{ item.avatar }}">添加好友</span>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}