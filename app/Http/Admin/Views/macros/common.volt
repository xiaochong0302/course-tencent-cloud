{%- macro client_type(value) %}
    {% if value == 1 %}
        PC
    {% elseif value == 2 %}
        H5
    {% elseif value == 3 %}
        APP
    {% elseif value == 5 %}
        微信小程序
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}