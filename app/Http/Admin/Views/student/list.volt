{%- macro source_type_info(value) %}
    {% if value == 'free' %}
        <span class="layui-badge layui-bg-green">免费</span>
    {% elseif value == 'charge' %}
        <span class="layui-badge layui-bg-orange">付费</span>
    {% elseif value == 'import' %}
        <span class="layui-badge layui-bg-blue">导入</span>
    {% endif %}
{%- endmacro %}

<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a class="kg-back"><i class="layui-icon layui-icon-return"></i> 返回</a>
            {% if course %}
                <a><cite>{{ course.title }}</cite></a>
            {% endif %}
            <a><cite>学员管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.student.search'}) }}">
            <i class="layui-icon layui-icon-search"></i>搜索学员
        </a>
        {% if course %}
            <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.student.add'},{'course_id':course.id}) }}">
                <i class="layui-icon layui-icon-add-1"></i>添加学员
            </a>
        {% else %}
            <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.student.add'}) }}">
                <i class="layui-icon layui-icon-add-1"></i>添加学员
            </a>
        {% endif %}
    </div>
</div>

<table class="kg-table layui-table">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col width="12%">
    </colgroup>
    <thead>
    <tr>
        <th>基本信息</th>
        <th>学习情况</th>
        <th>成员来源</th>
        <th>有效期限</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>
                <p>课程：<a href="{{ url({'for':'admin.student.list'},{'course_id':item.course.id}) }}">{{ item.course.title }}</a></p>
                <p>学员：<a href="{{ url({'for':'admin.student.list'},{'user_id':item.user_id}) }}">{{ item.user.name }}（{{ item.user.id }}）</a></p>
            </td>
            <td>
                <p>进度：{{ item.progress }}%</p>
                <p>时长：{{ item.duration|total_duration }}</p>
            </td>
            <td>{{ source_type_info(item.source_type) }}</td>
            <td>{{ date('Y-m-d H:i',item.expiry_time) }}</td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.student.edit'},{'plan_id':item.id}) }}">编辑学员</a></li>
                        <li><a class="kg-learning" href="javascript:" url="{{ url({'for':'admin.student.learning'},{'plan_id':item.id}) }}">学习记录</a>
                        </li>
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