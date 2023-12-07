<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.wechat_oa'}) }}">
    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col width="15%">
            <col width="20%">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>层级</th>
            <th>名称</th>
            <th>链接</th>
        </tr>
        </thead>
        <tbody>
        {% for i,top in oa.menu %}
            <tr>
                <td>├──</td>
                <td><input class="layui-input" type="text" name="menu[{{ i }}][name]" value="{{ top.name }}" placeholder="一级菜单最多4个汉字" lay-verify="required"></td>
                <td><input class="layui-input" type="text" name="menu[{{ i }}][url]" value="{{ top.url }}" placeholder="网页链接"></td>
            </tr>
            {% for j,sub in top.children %}
                <tr>
                    <td><span style="padding: 0 15px;"></span>├──</td>
                    <td><input class="layui-input" type="text" name="menu[{{ i }}][children][{{ j }}][name]" value="{{ sub.name }}" placeholder="二级菜单最多7个汉字"></td>
                    <td><input class="layui-input" type="text" name="menu[{{ i }}][children][{{ j }}][url]" value="{{ sub.url }}" placeholder="网页链接"></td>
                </tr>
            {% endfor %}
        {% endfor %}
        </tbody>
    </table>
    <br>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="wechat.oa">
        </div>
    </div>
</form>

