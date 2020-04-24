{%- macro client_type(value) %}
    {% if value == 'desktop' %}
        <span class="layui-badge layui-bg-green">桌面端</span>
    {% elseif value == 'mobile' %}
        <span class="layui-badge layui-bg-blue">手机端</span>
    {% endif %}
{%- endmacro %}

{%- macro last_active_time(create_time, update_time) %}
    {% if update_time > 0 %}
        {{ date('Y-m-d H:i', update_time) }}
    {% else %}
        {{ date('Y-m-d H:i', create_time) }}
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
        <th>终端类型</th>
        <th>终端地址</th>
        <th>最后活跃</th>
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
            <td><a href="javascript:" class="kg-ip2region" title="查看位置" data-ip="{{ item.client_ip }}">{{ item.client_ip }}</a></td>
            <td>{{ last_active_time(item.create_time,item.update_time) }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}
{{ partial('partials/ip2region') }}