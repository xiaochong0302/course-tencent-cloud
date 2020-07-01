{% if pager.total_pages > 0 %}
    <div class="im-user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                <div class="layui-col-md2">
                    <div class="user-card" title="{{ item.about|e }}">
                        <div class="avatar">
                            <a href="javascript:"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                        </div>
                        <div class="name layui-elip" title="{{ item.name|e }}">{{ item.name }}</div>
                        <div class="action">
                            <a href="javascript:" class="layui-badge-rim apply-group" data-id="{{ item.id }}" data-name="{{ item.name }}" data-avatar="{{ item.avatar }}">加入群组</a>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}