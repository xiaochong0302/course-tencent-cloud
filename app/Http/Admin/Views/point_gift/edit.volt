{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.point_gift.update','id':gift.id}) %}

    {% if gift.type == 1 %}
        {{ partial('point_gift/edit_course') }}
    {% elseif gift.type == 2 %}
        {{ partial('point_gift/edit_goods') }}
    {% endif %}

{% endblock %}

{% block link_css %}

    {% if gift.type == 2 %}
        {{ css_link('lib/vditor/dist/index.css') }}
    {% endif %}

{% endblock %}

{% block include_js %}

    {% if gift.type == 2 %}
        {{ js_include('lib/vditor/dist/index.min.js') }}
        {{ js_include('admin/js/cover.upload.js') }}
        {{ js_include('admin/js/vditor.js') }}
    {% endif %}

{% endblock %}