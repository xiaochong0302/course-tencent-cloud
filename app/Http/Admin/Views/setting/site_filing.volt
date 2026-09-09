<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.site'}) }}">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">ICP备案号</label>
            <div class="kg-input-inline">
                <input class="layui-input" type="text" name="icp_sn" value="{{ site.icp_sn }}">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备案链接</label>
            <div class="kg-input-inline" style="width:500px;">
                <input class="layui-input" type="text" name="icp_link" value="{{ site.icp_link }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">ICP经营许可证号</label>
            <div class="kg-input-inline">
                <input class="layui-input" type="text" name="icp_op_sn" value="{{ site.icp_op_sn }}">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备案链接</label>
            <div class="kg-input-inline" style="width:500px;">
                <input class="layui-input" type="text" name="icp_op_link" value="{{ site.icp_op_link }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">公安备案号</label>
            <div class="kg-input-inline">
                <input class="layui-input" type="text" name="police_sn" value="{{ site.police_sn }}">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备案链接</label>
            <div class="kg-input-inline" style="width:500px;">
                <input class="layui-input" type="text" name="police_link" value="{{ site.police_link }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">企业信用代码</label>
            <div class="kg-input-inline">
                <input class="layui-input" type="text" name="company_sn" value="{{ site.company_sn }}">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">电子执照链接</label>
            <div class="kg-input-inline" style="width:500px;">
                <input class="layui-input" type="text" name="company_sn_link" value="{{ site.company_sn_link }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>
