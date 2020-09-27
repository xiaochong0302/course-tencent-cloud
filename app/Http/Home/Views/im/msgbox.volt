{% extends 'templates/layer.volt' %}

{% block content %}

    <div id="LAY_view"></div>

    <div id="LAY_page" class="pager" data-count="{{ pager.total_items }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/im.msgbox.js') }}

{% endblock %}