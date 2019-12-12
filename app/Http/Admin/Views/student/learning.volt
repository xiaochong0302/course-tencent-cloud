{%- macro client_type(value) %}
    {% if value == 'desktop' %}
        <span class="layui-badge layui-bg-green">桌面端</span>
    {% elseif value == 'mobile' %}
        <span class="layui-badge layui-bg-blue">手机端</span>
    {% endif %}
{%- endmacro %}

<table class="kg-table layui-table layui-form">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>课时信息</th>
        <th>学习时长</th>
        <th>客户端类型</th>
        <th>客户端地址</th>
        <th>创建时间</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>
                <p>课程：{{ item.course.title }}</p>
                <p>章节：{{ item.chapter.title }}</p>
            </td>
            <td>{{ item.duration|play_duration }}</td>
            <td>{{ client_type(item.client_type) }}</td>
            <td><a class="kg-ip2region" href="javascript:;" title="查看位置" ip="{{ item.client_ip }}">{{ item.client_ip }}</a></td>
            <td>{{ date('Y-m-d H:i',item.created_at) }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}
{{ partial('partials/ip2region') }}

<script>

    layui.use(['jquery', 'form'], function () {

        var $ = layui.jquery;
        var form = layui.form;

        form.on('switch(switch-locked)', function (data) {
            var courseId = $(this).attr('course-id');
            var userId = $(this).attr('user-id');
            var checked = $(this).is(':checked');
            var locked = checked ? 1 : 0;
            var tips = locked == 1 ? '确定要锁定用户？' : '确定要解锁用户？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/student/update',
                    data: {
                        course_id: courseId,
                        user_id: userId,
                        locked: locked
                    },
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