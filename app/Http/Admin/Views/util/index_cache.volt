{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.util.index_cache'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>首页缓存</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">轮播图</label>
            <div class="layui-input-block">
                <input type="radio" name="items[slide]" value="1" title="是">
                <input type="radio" name="items[slide]" value="0" title="否" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推荐课程</label>
            <div class="layui-input-block">
                <input type="radio" name="items[featured_course]" value="1" title="是">
                <input type="radio" name="items[featured_course]" value="0" title="否" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新上课程</label>
            <div class="layui-input-block">
                <input type="radio" name="items[new_course]" value="1" title="是">
                <input type="radio" name="items[new_course]" value="0" title="否" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">免费课程</label>
            <div class="layui-input-block">
                <input type="radio" name="items[free_course]" value="1" title="是">
                <input type="radio" name="items[free_course]" value="0" title="否" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会员课程</label>
            <div class="layui-input-block">
                <input type="radio" name="items[vip_course]" value="1" title="是">
                <input type="radio" name="items[vip_course]" value="0" title="否" checked="checked">
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