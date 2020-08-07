{% extends 'templates/main.volt' %}

{% block content %}

    <pre class="layui-code" id="kg-code"></pre>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {
            var $ = layui.jquery;
            var obj = JSON.parse('{{ audit.req_data }}');
            var str = JSON.stringify(obj, undefined, 2);
            $('#kg-code').html(str);
        });

    </script>

{% endblock %}