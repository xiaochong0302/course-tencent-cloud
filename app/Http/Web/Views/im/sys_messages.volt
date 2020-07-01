{%- macro message_info(item) %}

    {% set item_type = item.item_type %}
    {% set item_info = item.item_info %}

    {% if item_type == '1' %}
        {% set sender = item_info.sender %}
        {% set group = item_info.group %}
        {% set remark = item_info.remark ? '附言：' ~ item_info.remark : '' %}
        <li data-id="{{ item.id }}">
            <a href="#" target="_blank"><img src="{{ sender.avatar }}" class="layui-circle layim-msgbox-avatar"></a>
            <p class="layim-msgbox-user" data-id="{{ sender.id }}" data-name="{{ sender.name }}" data-avatar="{{ sender.avatar }}" data-group="{{ group.id }}">
                <a href="#" target="_blank">{{ sender.name }}</a>
                <span>{{ date('Y-m-d H:i:s',item.create_time) }}</span>
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
            <p><em>系统：</em>{{ item_info.sender.name }} 接受了你的好友申请<span>{{ date('Y-m-d H:i:s',item.create_time) }}</span></p>
        </li>
    {% elseif item_type == '3' %}
        <li class="layim-msgbox-system">
            <p><em>系统：</em>{{ item_info.sender.name }} 拒绝了你的好友申请<span>{{ date('Y-m-d H:i:s',item.create_time) }}</span></p>
        </li>
    {% elseif item_type == '4' %}
        {% set remark = item_info.remark ? '附言：' ~ item_info.remark : '' %}
        <li data-id="{{ item.id }}">
            <a href="#" target="_blank"><img src="{{ item_info.sender.avatar }}" class="layui-circle layim-msgbox-avatar"></a>
            <p class="layim-msgbox-user">
                <a href="#" target="_blank">{{ item_info.sender.name }}</a>
                <span>{{ date('Y-m-d H:i:s',item.create_time) }}</span>
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
            <p><em>系统：</em>{{ item_info.sender.name }} 接受了你的入群申请<span>{{ date('Y-m-d H:i:s',item.create_time) }}</span></p>
        </li>
    {% elseif item_type == '6' %}
        <li class="layim-msgbox-system">
            <p><em>系统：</em>{{ item_info.sender.name }} 拒绝了你的入群申请<span>{{ date('Y-m-d H:i:s',item.create_time) }}</span></p>
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