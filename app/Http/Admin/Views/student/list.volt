<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a class="kg-back"><i class="layui-icon layui-icon-return"></i> 返回</a>
            <a><cite>学员管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.student.search'}) }}">
            <i class="layui-icon layui-icon-search"></i>搜索学员
        </a>
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.student.add'},{'course_id':course_id}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加学员
        </a>
    </div>
</div>

<table class="kg-table layui-table layui-form">
    <colgroup>
        <col>
        <col>
        <col>
        <col width="10%">
    </colgroup>
    <thead>
    <tr>
        <th>基本信息</th>
        <th>学习情况</th>
        <th>有效期限</th>
        <th>锁定</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>
                <p>课程：{{ item.course.title }}</p>
                <p>学员：{{ item.user.name }}</p>
            </td>
            <td>
                <div class="kg-progress">
                    <div class="layui-progress layui-progress" lay-showPercent="yes">
                        <div class="layui-progress-bar layui-bg-green" lay-percent="{{ item.progress }}%"></div>
                    </div>
                </div>
                <p>时长：{{ item.duration|total_duration }}</p>
            </td>
            <td>{{ date('Y-m-d H:i',item.expire_time) }}</td>
            <td><input type="checkbox" name="locked" value="1" lay-filter="switch-locked" lay-skin="switch" lay-text="是|否" course-id="{{ item.course_id }}" user-id="{{ item.user_id }}" {% if item.locked == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.student.edit'},{'course_id':item.course_id,'user_id':item.user_id}) }}">编辑学员</a></li>
                        <li><a class="kg-learning" href="javascript:" url="{{ url({'for':'admin.student.learning'},{'course_id':item.course_id,'user_id':item.user_id}) }}">学习记录</a></li>
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

        $('.kg-learning').on('click', function () {
            var url = $(this).attr('url');
            layer.open({
                id: 'xm-course',
                type: 2,
                title: '学习记录',
                resize: false,
                area: ['800px', '450px'],
                content: [url]
            });
        });

    });

</script>