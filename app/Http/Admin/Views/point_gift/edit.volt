{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.point_gift.update','id':gift.id}) %}

    {% if gift.type == 1 %}
        {{ partial('point_gift/edit_course') }}
    {% elseif gift.type == 3 %}
        {{ partial('point_gift/edit_vip') }}
    {% elseif gift.type == 2 %}
        {{ partial('point_gift/edit_goods') }}
    {% endif %}

{% endblock %}

{% block include_js %}

    {% if gift.type == 2 %}
        {{ js_include('lib/kindeditor/kindeditor.min.js') }}
        {{ js_include('admin/js/content.editor.js') }}
        {{ js_include('admin/js/cover.upload.js') }}
    {% endif %}

{% endblock %}