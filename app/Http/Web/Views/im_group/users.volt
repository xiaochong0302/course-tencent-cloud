<div class="group-user-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for item in pager.items %}
            {% set user_url = url({'for':'web.user.show','id':item.id}) %}
            <div class="layui-col-md3">
                <div class="user-card">
                    {% if item.vip == 1 %}
                        <span class="layui-badge layui-bg-orange vip">VIP</span>
                    {% endif %}
                    <div class="avatar">
                        <a href="{{ user_url }}" title="{{ item.about }}">
                            <img src="{{ item.avatar }}" alt="{{ item.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ user_url }}" title="{{ item.about }}">{{ item.name }}</a>
                    </div>
                    <div class="action">
                        <span class="layui-btn layui-btn-xs apply-friend">添加好友</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>
{{ partial('partials/pager_ajax') }}