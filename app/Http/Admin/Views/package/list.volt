<div class="kg-nav">
    <div class="kg-nav-left">
        <span c.lass="layui-breadcrumb">
            <a><cite>套餐管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.package.add'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加套餐
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
        <th>课程数</th>
        <th>市场价</th>
        <th>会员价</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>{{ item.id }}</td>
            <td><a href="{{ url({'for':'admin.package.edit','id':item.id}) }}">{{ item.title }}</a></td>
            <td><span class="layui-badge layui-bg-gray">{{ item.course_count }}</span></td>
            <td>￥{{ item.market_price }}</td>
            <td>￥{{ item.vip_price }}</td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="switch-published" package-id="{{ item.id }}" {% if item.published == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.package.edit','id':item.id}) }}">编辑</a></li>
                        <li><a href="javascript:" url="{{ url({'for':'admin.package.delete','id':item.id}) }}" class="kg-delete">删除</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}

<script>

    layui.use(['jquery', 'form'], function () {

        var $ = layui.jquery;
        var form = layui.form;

        form.on('switch(switch-published)', function (data) {
            var packageId = $(this).attr('package-id');
            var checked = $(this).is(':checked');
            var published = checked ? 1 : 0;
            var tips = published === 1 ? '确定要发布套餐？' : '确定要下架套餐？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/package/' + packageId + '/update',
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