{%- macro target_info(value) %}
    {% if value == 'course' %}
        <span class="layui-badge layui-bg-green">课程</span>
    {% elseif value == 'page' %}
        <span class="layui-badge layui-bg-blue">单页</span>
    {% elseif value == 'link' %}
        <span class="layui-badge layui-bg-orange">链接</span>
    {% endif %}
{%- endmacro %}

<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>轮播管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.slide.add'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加轮播
        </a>
    </div>
</div>

<table class="kg-table layui-table layui-form">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col width="12%">
    </colgroup>
    <thead>
    <tr>
        <th>编号</th>
        <th>标题</th>
        <th>目标类型</th>
        <th>有效期限</th>
        <th>排序</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>{{ item.id }}</td>
            <td>{{ item.title }}</td>
            <td>{{ target_info(item.target) }}</td>
            <td>
                <p>开始：{{ date('Y-m-d H:i',item.start_time) }}</p>
                <p>结束：{{ date('Y-m-d H:i',item.end_time) }}</p>
            </td>
            <td><input class="layui-input kg-priority-input" type="text" name="priority" value="{{ item.priority }}" slide-id="{{ item.id }}" title="数值越小排序越靠前"></td>
            <td><input type="checkbox" name="published" value="1" lay-filter="switch-published" lay-skin="switch" lay-text="是|否" slide-id="{{ item.id }}"
                       {% if item.published == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.slide.edit','id':item.id}) }}">编辑</a></li>
                        <li><a href="javascript:" url="{{ url({'for':'admin.slide.delete','id':item.id}) }}" class="kg-delete">删除</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}

<script>

    layui.use(['jquery', 'form', 'layer'], function () {

        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        $('input[name=priority]').on('change', function () {
            var priority = $(this).val();
            var slideId = $(this).attr('slide-id');
            $.ajax({
                type: 'POST',
                url: '/admin/slide/' + slideId + '/update',
                data: {priority: priority},
                success: function (res) {
                    layer.msg(res.msg, {icon: 1});
                },
                error: function (xhr) {
                    var json = JSON.parse(xhr.responseText);
                    layer.msg(json.msg, {icon: 2});
                }
            });
        });

        form.on('switch(switch-published)', function (data) {

            var slideId = $(this).attr('slide-id');
            var checked = $(this).is(':checked');
            var published = checked ? 1 : 0;
            var tips = published == 1 ? '确定要发布轮播？' : '确定要下架轮播？';

            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/slide/' + slideId + '/update',
                    data: {published: published},
                    success: function (res) {
                        layer.msg(res.msg, {icon: 1});
                    },
                    error: function (xhr) {
                        var json = JSON.parse(xhr.responseText);
                        layer.msg(json.msg, {icon: 2});
                        data.elem.checked = !checked;
                        form.render();
                    }
                });
            }, function () {
                data.elem.checked = !checked;
                form.render();
            });
        });

    });

</script>