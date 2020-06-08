{% if pager.total_pages > 0 %}
    <div class="vip-user-list clearfix">
        {% for item in pager.items %}
            {% set user_url = url({'for':'web.user.show','id':item.id}) %}
            <div class="user-card" title="{{ item.about|e }}">
                <div class="avatar">
                    <a href="{{ user_url }}"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                </div>
                <div class="name"><a href="{{ user_url }}">{{ item.name }}</a></div>
                <div class="title"><span class="layui-badge layui-bg-orange">vip</span></div>
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
