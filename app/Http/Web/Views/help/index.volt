{% extends 'templates/full.volt' %}

{% block content %}

    <div class="page-info wrap">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>帮助中心</legend>
            <div class="layui-field-box">
                <div class="layui-collapse" lay-accordion="true">
                    {% for help in helps %}
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">{{ help.title }}</h2>
                            <div class="layui-colla-content help-content">{{ help.content }}</div>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </fieldset>
    </div>

{% endblock %}