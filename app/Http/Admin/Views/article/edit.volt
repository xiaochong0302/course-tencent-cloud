{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.article.update','id':article.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑文章</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            <li>搜索优化</li>
            <li>文章内容</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('article/edit_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('article/edit_seo') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('article/edit_desc') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('admin/js/content.editor.js') }}
    {{ js_include('admin/js/cover.upload.js') }}

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

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(source_type)', function (data) {
                var block = $('#source-url-block');
                if (data.value === '1') {
                    block.hide();
                } else {
                    block.show();
                }
            });

        });

    </script>

{% endblock %}
