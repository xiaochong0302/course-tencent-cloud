{% if pager.total_pages > 0 %}
    <div class="user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set group_url = url({'for':'web.im_group.show','id':item.id}) %}
                <div class="layui-col-md3">
                    <div class="user-card">
                        {% if item.type == 'course' %}
                            <span class="layui-badge layui-bg-green type">课</span>
                        {% elseif item.type == 'chat' %}
                            <span class="layui-badge layui-bg-blue type">聊</span>
                        {% endif %}
                        <div class="avatar">
                            <a href="{{ group_url }}" title="{{ item.about }}">
                                <img src="{{ item.avatar }}" alt="{{ item.name }}">
                            </a>
                        </div>
                        <div class="name layui-elip">
                            <a href="{{ group_url }}" title="{{ item.name }}">{{ item.name }}</a>
                        </div>
                        <div class="action">
                            <button class="layui-btn apply-group" data-id="{{ item.id }}">申请加入</button>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}