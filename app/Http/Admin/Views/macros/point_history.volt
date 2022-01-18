{%- macro event_point_info(value) %}
    {% if value > 0 %}
        <span class="layui-badge layui-bg-green">+{{ value }}</span>
    {% else %}
        <span class="layui-badge layui-bg-red">{{ value }}</span>
    {% endif %}
{%- endmacro %}

{%- macro event_type_info(value) %}
    {% if value == 1 %}
        订单消费
    {% elseif value == 2 %}
        积分兑换
    {% elseif value == 3 %}
        积分退款
    {% elseif value == 4 %}
        帐号注册
    {% elseif value == 5 %}
        站点访问
    {% elseif value == 6 %}
        课时学习
    {% elseif value == 7 %}
        课程评价
    {% elseif value == 8 %}
        微聊讨论
    {% endif %}
{%- endmacro %}

{%- macro event_item_info(history) %}
    {% set event_info = history.event_info %}
    {% if history.event_type == 1 %}
        <p class="order">{{ event_info.order.subject }}</p>
    {% elseif history.event_type == 2 %}
        <p class="gift">{{ event_info.point_gift_redeem.gift_name }}</p>
    {% elseif history.event_type == 3 %}
        <span class="none">{{ event_info.point_gift_redeem.gift_name }}</span>
    {% elseif history.event_type == 4 %}
        <span class="none">N/A</span>
    {% elseif history.event_type == 5 %}
        <span class="none">N/A</span>
    {% elseif history.event_type == 6 %}
        <p class="course">课程：{{ event_info.course.title }}</p>
        <p class="chapter">章节：{{ event_info.chapter.title }}</p>
    {% elseif history.event_type == 7 %}
        <p class="course">{{ event_info.course.title }}</p>
    {% elseif history.event_type == 8 %}
        <span class="none">N/A</span>
    {% endif %}
{%- endmacro %}