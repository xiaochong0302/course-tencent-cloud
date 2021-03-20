{%- macro item_type_info(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 2 %}
        套餐
    {% elseif value == 3 %}
        会员
    {% endif %}
{%- endmacro %}

{%- macro item_full_info(item_type,item_info) %}
    {% if item_type == 1 %}
        {% set course = item_info.course %}
        <p>名称：{{ course.title }}（{{ course.id }}）</p>
        <p>类型：{{ item_type_info(item_type) }}　价格：{{ '￥%0.2f'|format(course.market_price) }}</p>
    {% elseif item_type == 2 %}
        {% set package = item_info.package %}
        <p>名称：{{ package.title }}（{{ package.id }}）</p>
        <p>类型：{{ item_type_info(item_type) }}　价格：{{ '￥%0.2f'|format(package.market_price) }}</p>
    {% elseif item_type == 3 %}
        {% set vip = item_info.vip %}
        <p>期限：{{ '%d个月'|format(vip.expiry) }}（{{ vip.id }}）</p>
        <p>类型：{{ item_type_info(item_type) }}　价格：{{ '￥%0.2f'|format(vip.price) }}</p>
    {% endif %}
{%- endmacro %}

{%- macro schedules_info(schedules) %}
    {% for value in schedules %}
        <span class="layui-badge layui-bg-gray">{{ value }}点</span>
    {% endfor %}
{%- endmacro %}