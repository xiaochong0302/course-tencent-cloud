{%- macro redeem_status_info(value) %}
    {% if value == 1 %}
        <span class="status">处理中</span>
    {% elseif value == 2 %}
        <span class="status">已完成</span>
    {% elseif value == 3 %}
        <span class="status">已失败</span>
    {% endif %}
{%- endmacro %}

{%- macro gift_type_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green type">课程</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-blue type">商品</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-cyan type">现金</span>
    {% endif %}
{%- endmacro %}

{%- macro event_type_info(value) %}
    {% if value == 1 %}
        <span class="type">订单消费</span>
    {% elseif value == 2 %}
        <span class="type">积分兑换</span>
    {% elseif value == 3 %}
        <span class="type">积分退款</span>
    {% elseif value == 4 %}
        <span class="type">帐号注册</span>
    {% elseif value == 5 %}
        <span class="type">站点访问</span>
    {% elseif value == 6 %}
        <span class="type">课时学习</span>
    {% elseif value == 7 %}
        <span class="type">课程评价</span>
    {% elseif value == 8 %}
        <span class="type">微聊讨论</span>
    {% endif %}
{%- endmacro %}

{%- macro event_detail_info(history) %}
    {% set event_info = history.event_info %}
    {% if history.event_type == 1 %}
        <p class="order">{{ event_info.order.subject }}</p>
    {% elseif history.event_type == 2 %}
        <p class="gift">{{ event_info.point_redeem.gift_name }}</p>
    {% elseif history.event_type == 3 %}
        <span class="none">{{ event_info.point_redeem.gift_name }}</span>
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