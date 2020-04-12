<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>出错啦</title>
    {{ icon_link("favicon.ico") }}
    {{ css_link("lib/layui/css/layui.css") }}
    {{ css_link("web/css/style.css") }}
    {{ js_include("lib/layui/layui.js") }}
</head>
<body class="layui-layout-body">
{{ content() }}
</body>
</html>