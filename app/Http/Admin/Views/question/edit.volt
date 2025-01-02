{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.question.update','id':question.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑问题</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            <li>搜索优化</li>
            <li>内容详情</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('question/edit_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('question/edit_seo') }}
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
    {{ js_include('admin/js/content.editor.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            xmSelect.render({
                el: '#xm-tag-ids',
                name: 'xm_tag_ids',
                max: 5,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_tags|json_encode }}
            });

        });

    </script>

{% endblock %}
