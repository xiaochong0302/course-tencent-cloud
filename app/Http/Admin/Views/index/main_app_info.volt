{% set gitee_url = 'https://gitee.com/koogua/course-tencent-cloud' %}
{% set github_url = 'https://github.com/xiaochong0302/course-tencent-cloud' %}

<div class="layui-card layui-text">
    <div class="layui-card-header">应用信息</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col width="25%">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td>当前版本</td>
                <td><a href="{{ gitee_url ~ '/releases/v' ~ app_info.version }}" target="_blank">{{ app_info.alias }} {{ app_info.version }}</a></td>
            </tr>
            <tr>
                <td>获取渠道</td>
                <td>
                    <a href="{{ gitee_url }}" target="_blank">Gitee</a>&nbsp;
                    <a href="{{ github_url }}" target="_blank">Github</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
