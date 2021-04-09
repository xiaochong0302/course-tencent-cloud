{% if pager.total_pages > 0 %}
    <div class="user-list vip-user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set user_url = url({'for':'home.user.show','id':item.id}) %}
                {% set item.title = item.title ? item.title : '暂露头角' %}
                {% set item.about = item.about ? item.about : '这个人很懒，什么都没留下' %}
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
                        <div class="title layui-elip">{{ item.title }}</div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
