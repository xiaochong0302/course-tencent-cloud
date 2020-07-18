{% if pager.total_pages > 0 %}
    <div class="im-user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                <div class="layui-col-md2">
                    <div class="user-card">
                        {% if item.vip == 1 %}
                            <span class="vip">会员</span>
                        {% endif %}
                        <div class="avatar">
                            <a href="javascript:" title="{{ item.about|e }}"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                        </div>
                        <div class="name layui-elip" title="{{ item.name|e }}">{{ item.name }}</div>
                        <div class="action">
                            <a href="javascript:" class="layui-badge-rim apply-friend" data-id="{{ item.id }}" data-name="{{ item.name }}" data-avatar="{{ item.avatar }}">加为好友</a>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}