<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.upload.default_img'}) }}">
    <div class="layui-form-item">
        <table class="layui-table" lay-size="lg" style="width:80%;">
            <colgroup>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>文件名称</th>
                <th>文件位置</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>用户头像</td>
                <td>public/static/admin/img/default/user_avatar.png</td>
            </tr>
            <tr>
                <td>课程封面</td>
                <td>public/static/admin/img/default/course_cover.png</td>
            </tr>
            <tr>
                <td>会员封面</td>
                <td>public/static/admin/img/default/vip_cover.png</td>
            </tr>
            <tr>
                <td>轮播封面</td>
                <td>public/static/admin/img/default/slide_cover.png</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">上传</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>
