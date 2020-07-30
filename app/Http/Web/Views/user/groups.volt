{% if pager.total_pages > 0 %}
    <div class="user-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set group_url = url({'for':'web.im_group.show','id':item.id}) %}
                <div class="layui-col-md3">
                    <div class="user-card">
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