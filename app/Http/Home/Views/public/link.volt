{% set target_url = request.get('url') %}

<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="webkit">
    <title>安全中心 - {{ site_info.title }}</title>
    {% if site_info.favicon %}
        {{ icon_link(site_info.favicon,false) }}
    {% else %}
        {{ icon_link('favicon.ico') }}
    {% endif %}
    {{ css_link("lib/layui/css/layui.css") }}
    {{ css_link("home/css/link.css") }}
</head>
<body>
<div class="link-wrap">
    <h3>页面跳转提示</h3>
    <div class="tips">您即将离开{{ site_info.title }}，请注意您的账号和财产安全。</div>
    <div class="url">{{ target_url }}</div>
    <div class="action">
        <a href="{{ target_url }}" class="layui-btn">继续访问</a>
    </div>
</div>
</body>
</html>
