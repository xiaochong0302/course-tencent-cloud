{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.point_gift_redeem.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索兑换</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">用户编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="用户编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">礼物编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="gift_id" placeholder="礼物编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">礼物类型</label>
            <div class="layui-input-block">
                <input type="radio" name="gift_type" value="1" title="课程">
                <input type="radio" name="gift_type" value="2" title="商品">
                <input type="radio" name="gift_type" value="3" title="现金">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">兑换状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="处理中">
                <input type="radio" name="status" value="2" title="已完成">
                <input type="radio" name="status" value="3" title="已失败">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}