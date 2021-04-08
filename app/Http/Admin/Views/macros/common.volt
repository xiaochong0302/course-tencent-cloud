{%- macro client_type(value) %}
    {% if value == 1 %}
        PC
    {% elseif value == 2 %}
        H5
    {% elseif value == 3 %}
        APP
    {% elseif value == 4 %}
        小程序
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}