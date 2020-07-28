{% extends 'templates/main.volt' %}

{% block content %}

    <div class="page-info wrap">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ page.title }}</legend>
            <div class="layui-field-box page-content">
                {{ page.content }}
            </div>
        </fieldset>
    </div>

{% endblock %}