<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            {% if parent.id > 0 %}
                <a class="kg-back" href="{{ url({'for':'admin.category.list'}) }}">
                    <i class="layui-icon layui-icon-return"></i> 返回
                </a>
                <a><cite>{{ parent.name }}</cite></a>
            {% endif %}
            <a><cite>分类管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.category.add'},{'parent_id':parent.id}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加分类
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
        <col>
        <col width="12%">
    </colgroup>
    <thead>
    <tr>
        <th>编号</th>
        <th>名称</th>
        <th>层级</th>
        <th>节点数</th>
        <th>课程数</th>
        <th>排序</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in categories %}
        <tr>
            <td>{{ item.id }}</td>
            {% if item.level < 2 %}
                <td><a href="{{ url({'for':'admin.category.list'}) }}?parent_id={{ item.id }}">{{ item.name }}</a></td>
            {% else %}
                <td>{{ item.name }}</td>
            {% endif %}
            <td><span class="layui-badge layui-bg-gray">{{ item.level }}</span></td>
            <td><span class="layui-badge layui-bg-gray">{{ item.child_count }}</span></td>
            <td><span class="layui-badge layui-bg-gray">{{ item.course_count }}</span></td>
            <td><input class="layui-input kg-priority-input" type="text" name="priority" value="{{ item.priority }}" category-id="{{ item.id }}" title="数值越小排序越靠前"></td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="switch-published" category-id="{{ item.id }}" {% if item.published == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.category.edit','id':item.id}) }}">编辑</a></li>
                        {% if item.deleted == 0 %}
                            <li><a href="javascript:" class="kg-delete" url="{{ url({'for':'admin.category.delete','id':item.id}) }}">删除</a></li>
                        {% else %}
                            <li><a href="javascript:" class="kg-restore" url="{{ url({'for':'admin.category.restore','id':item.id}) }}">还原</a></li>
                        {% endif %}
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

<script>

    layui.use(['jquery', 'form', 'layer'], function () {

        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        $('input[name=priority]').on('change', function () {
            var priority = $(this).val();
            var categoryId = $(this).attr('category-id');
            $.ajax({
                type: 'POST',
                url: '/admin/category/' + categoryId + '/update',
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
            var categoryId = $(this).attr('category-id');
            var checked = $(this).is(':checked');
            var published = checked ? 1 : 0;
            var tips = published === 1 ? '确定要发布分类？' : '确定要下线分类？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/category/' + categoryId + '/update',
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