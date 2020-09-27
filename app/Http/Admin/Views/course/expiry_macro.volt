{%- macro study_expiry_info(value) %}
    {% if value == 1 %}
        1个月
    {% elseif value == 3 %}
        3个月
    {% elseif value == 6 %}
        6个月
    {% elseif value == 12 %}
        12个月
    {% elseif value == 36 %}
        36个月
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro refund_expiry_info(value) %}
    {% if value == 7 %}
        7天
    {% elseif value == 14 %}
        14天
    {% elseif value == 30 %}
        30天
    {% elseif value == 90 %}
        90天
    {% elseif value == 180 %}
        180天
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}