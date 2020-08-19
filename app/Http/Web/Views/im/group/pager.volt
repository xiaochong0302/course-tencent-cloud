{%- macro type_info(value) %}
    {% if value == 'course' %}
        <span class="layui-badge layui-bg-green type">课</span>
    {% elseif value == 'chat' %}
        <span class="layui-badge layui-bg-blue type">聊</span>
    {% elseif value == 'staff' %}
        <span class="layui-badge layui-bg-red type">工</span>
    {% endif %}
{%- endmacro %}

<div class="group-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for item in pager.items %}
            {% set group_url = url({'for':'web.group.show','id':item.id}) %}
            {% set owner_url = url({'for':'web.user.show','id':item.owner.id}) %}
            {% set item.about = item.about ? item.about : '这家伙真懒，什么都没留下！' %}
            <div class="layui-col-md3">
                <div class="user-card">
                    {{ type_info(item.type) }}
                    <div class="avatar">
                        <a href="{{ group_url }}" title="{{ item.about }}">
                            <img src="{{ item.avatar }}" alt="{{ item.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ group_url }}" title="{{ item.name }}">{{ item.name }}</a>
                    </div>
                    <div class="owner">
                        <span>组长：<a href="{{ owner_url }}">{{ item.owner.name }}</a></span>
                    </div>
                    <div class="meta layui-elip">
                        <span>成员：{{ item.user_count }}</span>
                        <span>讨论：{{ item.msg_count }}</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>
{{ partial('partials/pager_ajax') }}