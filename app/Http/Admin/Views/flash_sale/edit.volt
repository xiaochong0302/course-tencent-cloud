{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/flash_sale') }}

    {% set sale.item_info = array_object(sale.item_info) %}
    {% set sale.start_time = sale.start_time > 0 ? date('Y-m-d H:i:s',sale.start_time) : '' %}
    {% set sale.end_time = sale.end_time > 0 ? date('Y-m-d H:i:s',sale.end_time) : '' %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.flash_sale.update','id':sale.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑商品</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">商品信息</label>
            <div class="layui-input-block gray">
                {{ item_full_info(sale.item_type,sale.item_info) }}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开始时间</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="start_time" autocomplete="off" value="{{ sale.start_time }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="end_time" autocomplete="off" value="{{ sale.end_time }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">参与场次</label>
            <div class="layui-input-block">
                <div id="xm-schedules"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">秒杀价格</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="price" value="{{ sale.price }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">秒杀库存</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="stock" value="{{ sale.stock }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是" {% if sale.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="否" {% if sale.published == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'laydate'], function () {

            xmSelect.render({
                el: '#xm-schedules',
                name: 'xm_schedules',
                data: {{ xm_schedules|json_encode }}
            });

            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name=start_time]',
                type: 'datetime'
            });

            laydate.render({
                elem: 'input[name=end_time]',
                type: 'datetime'
            });

        });

    </script>

{% endblock %}