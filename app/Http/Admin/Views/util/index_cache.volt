{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.util.index_cache'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>首页缓存</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">刷新内容</label>
            <div class="layui-input-block">
                <input type="checkbox" name="items[]" value="slide" title="轮播图" checked="checked">
                <input type="checkbox" name="items[]" value="featured_course" title="推荐课程" checked="checked">
                <input type="checkbox" name="items[]" value="new_course" title="新上课程" checked="checked">
                <input type="checkbox" name="items[]" value="free_course" title="免费课程" checked="checked">
                <input type="checkbox" name="items[]" value="vip_course" title="会员课程" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">刷新</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}