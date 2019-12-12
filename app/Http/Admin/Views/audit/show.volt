<pre class="layui-code" id="kg-code"></pre>

<script>

    layui.use(['jquery'], function () {
        var $ = layui.jquery;
        var obj = JSON.parse('{{ audit.req_data }}');
        var str = JSON.stringify(obj, undefined, 2);
        $('#kg-code').html(str);
    });

</script>
