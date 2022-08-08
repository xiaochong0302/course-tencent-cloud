{% extends 'templates/main.volt' %}

{% block content %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑问题</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            <li>内容详情</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('question/edit_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('question/edit_desc') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('lib/kindeditor/lang/zh-CN.js') }}
    {{ js_include('admin/js/content.editor.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            xmSelect.render({
                el: '#xm-tag-ids',
                name: 'xm_tag_ids',
                max: 3,
                filterable: true,
                filterMethod: function (val, item, index, prop) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_tags|json_encode }}
            });

        });

    </script>

{% endblock %}