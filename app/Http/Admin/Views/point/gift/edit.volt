{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.point_gift.update','id':gift.id}) %}

    {% if gift.type == 1 %}
        {{ partial('point/gift/edit_course') }}
    {% elseif gift.type == 2 %}
        {{ partial('point/gift/edit_goods') }}
    {% endif %}

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('admin/js/cover.upload.js') }}
    {{ js_include('admin/js/vditor.js') }}

{% endblock %}