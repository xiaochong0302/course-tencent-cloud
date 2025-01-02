{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.package.update','id':package.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑套餐</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">封面</label>
            <div class="layui-input-inline">
                <img id="img-cover" class="kg-cover" src="{{ package.cover }}">
                <input type="hidden" name="cover" value="{{ package.cover }}">
            </div>
            <div class="layui-input-inline" style="padding-top:35px;">
                <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ package.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">简介</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="summary">{{ package.summary }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">相关课程</label>
            <div class="layui-input-block">
                <div id="xm-course-ids"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">市场价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="market_price" value="{{ package.market_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">会员价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="vip_price" value="{{ package.vip_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
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
    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            xmSelect.render({
                el: '#xm-course-ids',
                name: 'xm_course_ids',
                max: 15,
                autoRow: true,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_courses|json_encode }}
            });

        });

    </script>

{% endblock %}
