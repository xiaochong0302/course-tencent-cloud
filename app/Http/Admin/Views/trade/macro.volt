{%- macro scene_type(channel,scene) %}
    {% if channel == 1 %}
        {% if scene == 'scan' %}
            扫码
        {% elseif scene == 'mini' %}
            小程序
        {% elseif scene == 'h5' %}
            H5
        {% endif %}
    {% elseif channel == 2 %}
        {% if scene == 'jsapi' %}
            公众号
        {% elseif scene == 'native' %}
            扫码
        {% elseif scene == 'mini' %}
            小程序
        {% elseif scene == 'h5' %}
            H5
        {% endif %}
    {% endif %}
{%- endmacro %}

{%- macro trade_status(value) %}
    {% if value == 1 %}
        待支付
    {% elseif value == 2 %}
        已完成
    {% elseif value == 3 %}
        已关闭
    {% elseif value == 4 %}
        已退款
    {% endif %}
{%- endmacro %}
