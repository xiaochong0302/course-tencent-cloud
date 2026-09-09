<div class="layui-card layui-text">
    <div class="layui-card-header">服务器信息</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col width="25%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td>磁盘空间</td>
                <td>{{ server_info.disk.total }} 已用 {{ server_info.disk.usage }} 使用率 {{ server_info.disk.percent }}%</td>
            </tr>
            <tr>
                <td>内存空间</td>
                <td>{{ server_info.memory.total }} 已用 {{ server_info.memory.usage }} 使用率 {{ server_info.memory.percent }}%</td>
            </tr>
            <tr>
                <td>系统负载</td>
                <td>{{ server_info.cpu[0] }} {{ server_info.cpu[1] }} {{ server_info.cpu[2] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
