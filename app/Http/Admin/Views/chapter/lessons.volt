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
{% elseif course.model == 'read' %}
    {{ partial('chapter/lessons_read') }}
{% endif %}

<script>

    layui.use(['jquery', 'layer', 'form'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;

        form.on('switch(free)', function (data) {
            var checked = $(this).is(':checked');
            var free = checked ? 1 : 0;
            var url = $(this).attr('data-url');
            $.ajax({
                type: 'POST',
                url: url,
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

    });

</script>