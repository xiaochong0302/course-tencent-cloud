<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a class="kg-back" href="{{ url({'for':'admin.course.chapters','id':course.id}) }}">
                <i class="layui-icon layui-icon-return"></i> 返回
            </a>
            <a><cite>{{ course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
            <a><cite>课时管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'chapter'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加章
        </a>
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.chapter.add'},{'course_id':course.id,'parent_id':chapter.id,'type':'lesson'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加课
        </a>
    </div>
</div>

{% if course.model == 'vod' %}
    {{ partial('chapter/lessons_vod') }}
{% elseif course.model == 'live' %}
    {{ partial('chapter/lessons_live') }}
{% elseif course.model == 'article' %}
    {{ partial('chapter/lessons_article') }}
{% endif %}

<script>

    layui.use(['jquery', 'layer', 'form'], function () {

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

        form.on('switch(switch-free)', function (data) {
            var chapterId = $(this).attr('chapter-id');
            var checked = $(this).is(':checked');
            var free = checked ? 1 : 0;
            $.ajax({
                type: 'POST',
                url: '/admin/chapter/' + chapterId + '/update',
                data: {free: free},
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
        });

        form.on('switch(switch-published)', function (data) {
            var chapterId = $(this).attr('chapter-id');
            var checked = $(this).is(':checked');
            var published = checked ? 1 : 0;
            $.ajax({
                type: 'POST',
                url: '/admin/chapter/' + chapterId + '/update',
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
        });

    });

</script>