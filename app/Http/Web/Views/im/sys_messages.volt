{%- macro message_info(item) %}

    {% set item_type = item.item_type %}
    {% set item_info = item.item_info %}
    {% set sender = item_info.sender %}
    {% set sender_url = url({'for':'web.user.show','id':sender.id}) %}

    {% if item_type == '1' %}
        {% set group = item_info.group %}
        {% set remark = item_info.remark ? '附言：' ~ item_info.remark : '' %}
        <li data-id="{{ item.id }}">
            <a href="{{ sender_url }}" target="_blank"><img class="layui-circle layim-msgbox-avatar" alt="{{ sender.name }}" src="{{ sender.avatar }}"></a>
            <p class="layim-msgbox-user" data-id="{{ sender.id }}" data-name="{{ sender.name }}" data-avatar="{{ sender.avatar }}" data-group="{{ group.id }}">
                <a href="{{ sender_url }}" target="_blank">{{ sender.name }}</a>
                <span>{{ item.create_time|time_ago }}</span>
            </p>
            <p class="layim-msgbox-content">申请添加你为好友 <span>{{ remark }}</span></p>
            <p class="layim-msgbox-btn">
                {% if item_info.status == 'pending' %}
                    <button class="layui-btn layui-btn-small" data-type="acceptFriend">接受</button>
                    <button class="layui-btn layui-btn-small layui-btn-primary" data-type="refuseFriend">拒绝</button>
                {% elseif item_info.status == 'accepted' %}
                    已同意
                {% elseif item_info.status == 'refused' %}
                    已拒绝
                {% endif %}
            </p>
        </li>
    {% elseif item_type == '2' %}
        <li class="layim-msgbox-system">
            <p>
                <em>系统：</em>
                <a href="{{ sender_url }}" target="_blank">{{ sender.name }}</a>
                接受了你的好友申请<span>{{ item.create_time|time_ago }}</span>
            </p>
        </li>
    {% elseif item_type == '3' %}
        <li class="layim-msgbox-system">
            <p>
                <em>系统：</em>
                <a href="{{ sender_url }}" target="_blank">{{ sender.name }}</a>
                拒绝了你的好友申请<span>{{ item.create_time|time_ago }}</span>
            </p>
        </li>
    {% elseif item_type == '4' %}
        {% set remark = item_info.remark ? '附言：' ~ item_info.remark : '' %}
        <li data-id="{{ item.id }}">
            <a href="{{ sender_url }}" target="_blank"><img class="layui-circle layim-msgbox-avatar" alt="{{ sender.name }}" src="{{ sender.avatar }}"></a>
            <p class="layim-msgbox-user">
                <a href="{{ sender_url }}" target="_blank">{{ sender.name }}</a>
                <span>{{ item.create_time|time_ago }}</span>
            </p>
            <p class="layim-msgbox-content">申请加入群组 <span>{{ remark }}</span></p>
            <p class="layim-msgbox-btn">
                {% if item_info.status == 'pending' %}
                    <button class="layui-btn layui-btn-small" data-type="acceptGroup">接受</button>
                    <button class="layui-btn layui-btn-small layui-btn-primary" data-type="refuseGroup">拒绝</button>
                {% elseif item_info.status == 'accepted' %}
                    已同意
                {% elseif item_info.status == 'refused' %}
                    已拒绝
                {% endif %}
            </p>
        </li>
    {% elseif item_type == '5' %}
        <li class="layim-msgbox-system">
            <p>
                <em>系统：</em>
                <a href="{{ sender_url }}" target="_blank">{{ sender.name }}</a>
                接受了你的入群申请<span>{{ item.create_time|time_ago }}</span>
            </p>
        </li>
    {% elseif item_type == '6' %}
        <li class="layim-msgbox-system">
            <p>
                <em>系统：</em>
                <a href="{{ sender_url }}" target="_blank">{{ sender.name }}</a>
                拒绝了你的入群申请<span>{{ item.create_time|time_ago }}</span>
            </p>
        </li>
    {% endif %}
{%- endmacro %}

{% if pager.items %}
    <ul class="layim-msgbox">
        {% for item in pager.items %}
            {{ message_info(item) }}
        {% endfor %}
    </ul>
{% endif %}