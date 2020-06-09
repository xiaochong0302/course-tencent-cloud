{% if pager.total_pages > 0 %}
    <div class="vip-user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set user_about = item.about ? item.about|e : '这个人很懒，什么都没留下' %}
                {% set user_url = url({'for':'web.user.show','id':item.id}) %}
                <div class="layui-col-md3">
                    <div class="user-card" title="{{ user_about }}">
                        <div class="avatar">
                            <a href="{{ user_url }}"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                        </div>
                        <div class="name layui-elip">
                            <a href="{{ user_url }}">{{ item.name }}</a>
                        </div>
                        <div class="title layui-elip">
                            <span class="layui-badge layui-bg-orange">vip</span>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
