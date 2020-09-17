{% extends 'templates/layer.volt' %}

{% block content %}

    <div class="bg-wrap">
        <div class="im-user-list clearfix">
            <div class="layui-row layui-col-space20">
                {% for item in pager.items %}
                    {% set delete_url = url({'for':'home.igm.delete_user','gid':group.id,'uid':item.id}) %}
                    <div class="layui-col-md2">
                        <div class="user-card">
                            {% if item.vip == 1 %}
                                <span class="vip">会员</span>
                            {% endif %}
                            <div class="avatar">
                                <a href="javascript:" title="{{ item.about }}"><img src="{{ item.avatar }}" alt="{{ item.name }}"></a>
                            </div>
                            <div class="name layui-elip" title="{{ item.name }}">{{ item.name }}</div>
                            <div class="action">
                                <button class="layui-btn kg-delete" data-tips="你确定要移除该用户吗？" data-url="{{ delete_url }}">移除</button>
                            </div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
        {{ partial('partials/pager') }}
    </div>

{% endblock %}

