{%- macro source_type(value) %}
    {% if value == 1 %}
        免费
    {% elseif value == 2 %}
        付费
    {% elseif value == 3 %}
        畅学
    {% elseif value == 4 %}
        分配
    {% elseif value == 5 %}
        积分兑换
    {% elseif value == 6 %}
        抽奖兑换
    {% elseif value == 7 %}
        教师
    {% elseif value == 10 %}
        试听
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}
