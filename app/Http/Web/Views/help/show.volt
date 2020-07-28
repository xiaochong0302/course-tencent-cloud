{% extends 'templates/main.volt' %}

{% block content %}

    <div class="page-info wrap">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ help.title }}</legend>
            <div class="layui-field-box page-content">
                {{ help.content }}
            </div>
        </fieldset>
    </div>

{% endblock %}