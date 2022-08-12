{% if pager.total_pages > 0 %}
    <div class="user-list vip-user-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set user_url = url({'for':'home.user.show','id':item.id}) %}
                <div class="layui-col-md2">
                    <div class="user-card">
                        <div class="avatar">
                            <a href="{{ user_url }}" title="{{ item.about }}" target="_blank">
                                <img src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                            </a>
                        </div>
                        <div class="name layui-elip">
                            <a href="{{ user_url }}" title="{{ item.about }}" target="_blank">{{ item.name }}</a>
                        </div>
                        <div class="title layui-elip">{{ item.title|default('暂露头角') }}</div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
