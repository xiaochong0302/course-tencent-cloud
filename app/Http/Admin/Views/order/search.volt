<form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.order.list'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>搜索订单</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">用户编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="user_id" placeholder="用户编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">订单编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="sn" placeholder="订单编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">订单类型</label>
        <div class="layui-input-block">
            <input type="radio" name="item_type" value="course" title="课程">
            <input type="radio" name="item_type" value="package" title="套餐">
            <input type="radio" name="item_type" value="reward" title="打赏">
            <input type="radio" name="item_type" value="vip" title="会员">
            <input type="radio" name="item_type" value="test" title="测试">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">订单状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="pending" title="待支付">
            <input type="radio" name="status" value="finished" title="已完成">
            <input type="radio" name="status" value="closed" title="已关闭">
            <input type="radio" name="status" value="refunded" title="已退款">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">创建时间</label>
        <div class="layui-input-inline">
            <input class="layui-input time-range" type="text" name="start_time" autocomplete="off">
        </div>
        <div class="layui-form-mid"> - </div>
        <div class="layui-input-inline">
            <input class="layui-input time-range" type="text" name="end_time" autocomplete="off">
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

<script>

    layui.use(['form', 'laydate'], function () {

        var laydate = layui.laydate;

        lay('.time-range').each(function () {
            laydate.render({
                elem: this,
                type: 'datetime',
                trigger: 'click'
            });
        });

    });

</script>