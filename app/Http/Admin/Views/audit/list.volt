<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>操作记录</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.audit.search'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>搜索记录
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
        <col width="10%">
    </colgroup>
    <thead>
    <tr>
        <th>用户编号</th>
        <th>用户名称</th>
        <th>用户IP</th>
        <th>请求路由</th>
        <th>请求路径</th>
        <th>请求时间</th>
        <th>请求内容</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>{{ item.user_id }}</td>
            <td>{{ item.user_name }}</td>
            <td><a class="kg-ip2region" href="javascript:;" title="查看位置" ip="{{ item.user_ip }}">{{ item.user_ip }}</a></td>
            <td>{{ item.req_route }}</td>
            <td>{{ item.req_path }}</td>
            <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            <td align="center">
                <button class="kg-view layui-btn layui-btn-sm" audit-id="{{ item.id }}">浏览</button>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}
{{ partial('partials/ip2region') }}

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;

        $('.kg-view').on('click', function () {
            var auditId = $(this).attr('audit-id');
            var url = '/admin/audit/' + auditId + '/show';
            layer.open({
                type: 2,
                title: '请求内容',
                resize: false,
                area: ['640px', '360px'],
                content: [url]
            });
        });

    });

</script>