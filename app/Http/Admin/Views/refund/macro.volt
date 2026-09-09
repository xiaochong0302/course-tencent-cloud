{%- macro refund_status(value) %}
    {% if value == 1 %}
        待处理
    {% elseif value == 2 %}
        已取消
    {% elseif value == 3 %}
        已审核
    {% elseif value == 4 %}
        已拒绝
    {% elseif value == 5 %}
        已完成
    {% elseif value == 6 %}
        已失败
    {% endif %}
{%- endmacro %}