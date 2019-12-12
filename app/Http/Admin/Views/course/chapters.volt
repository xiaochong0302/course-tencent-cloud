<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a class="kg-back" href="{{ url({'for':'admin.course.list'}) }}"><i class="layui-icon layui-icon-return"></i> 返回</a>
            <a><cite>{{ course.title }}</cite></a>
            <a><cite>章节管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'chapter'}) }}"><i class="layui-icon layui-icon-add-1"></i>添加章</a>
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'lesson'}) }}"><i class="layui-icon layui-icon-add-1"></i>添加课</a>
    </div>
</div>

<table class="kg-table layui-table layui-form">
    <colgroup>
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
        <th>课时数</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in chapters %}
        <tr>
            <td>{{ item.id }}</td>
            <td>
                <span><a href="{{ url({'for':'admin.chapter.lessons','id':item.id}) }}">{{ item.title }}</a></span>
                <span class="layui-badge layui-bg-green">章</span>
            </td>
            <td>
                <a href="{{ url({'for':'admin.chapter.lessons','id':item.id}) }}">
                    <span class="layui-badge layui-bg-green">{{ item.lesson_count }}</span>
                </a>
            </td>
            <td><input class="layui-input kg-priority-input" type="text" name="priority" value="{{ item.priority }}" chapter-id="{{ item.id }}"></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.chapter.edit','id':item.id}) }}">编辑</a></li>
                        <li><a href="javascript:;" class="kg-delete" url="{{ url({'for':'admin.chapter.delete','id':item.id}) }}">删除</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

<script>

    layui.use(['jquery', 'layer', 'form', 'element'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;

        $('input[name=priority]').on('change', function () {

            var priority = $(this).val();
            var chapterId = $(this).attr('chapter-id');

            $.ajax({
                type: 'POST',
                url: '/admin/chapter/' + chapterId + '/update',
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

    });

</script>