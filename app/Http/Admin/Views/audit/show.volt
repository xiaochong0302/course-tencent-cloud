{% extends 'templates/main.volt' %}

{% block content %}

    {% set audit.req_data = audit.req_data ? audit.req_data : '{}' %}

    <pre class="layui-code" id="kg-code">{{ audit.req_data }}</pre>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;
            var $code = $('#kg-code');
            var obj = JSON.parse($code.html());
            var str = JSON.stringify(obj, undefined, 2);

            $code.html(str);
        });

    </script>

{% endblock %}