<!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token-key" content="{{ security.getTokenKey() }}">
    <meta name="csrf-token-value" content="{{ security.getTokenValue() }}">
    <title>管理后台</title>
    {{ icon_link("favicon.ico") }}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('lib/layui/extends/dropdown.css') }}
    {{ css_link('admin/css/style.css') }}
    {{ js_include('lib/layui/layui.js') }}
    {{ js_include('admin/js/common.js') }}
</head>
<body class="kg-body">
{{ content() }}
</body>
</html>