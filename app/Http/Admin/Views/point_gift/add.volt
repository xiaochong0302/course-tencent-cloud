{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.point_gift.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加礼品</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">礼品类型</label>
            <div class="layui-input-block">
                {% for value,title in types %}
                    <input type="radio" name="type" value="{{ value }}" title="{{ title }}" lay-filter="type">
                {% endfor %}
            </div>
        </div>
        <div id="block-1" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">课程选择</label>
                <div class="layui-input-block">
                    <div id="xm-course-id"></div>
                </div>
            </div>
        </div>
        <div id="block-3" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">会员选择</label>
                <div class="layui-input-block">
                    <div id="xm-vip-id"></div>
                </div>
            </div>
        </div>
        <div id="block-2" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">商品名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="name" value="">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
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

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            xmSelect.render({
                el: '#xm-course-id',
                name: 'xm_course_id',
                radio: true,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_courses|json_encode }}
            });

            xmSelect.render({
                el: '#xm-vip-id',
                name: 'xm_vip_id',
                radio: true,
                data: {{ xm_vips|json_encode }}
            });

            form.on('radio(type)', function (data) {
                $('.block').hide();
                $('#block-' + data.value).show();
            });

        });

    </script>

{% endblock %}
