<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开始日期</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="attrs[start_date]" autocomplete="off" value="{{ course.attrs['start_date'] }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">结束日期</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="attrs[end_date]" autocomplete="off" value="{{ course.attrs['end_date'] }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">上课地点</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="attrs[location]" value="{{ course.attrs['location'] }}" placeholder="可以用于导航的地理位置" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">人数限制</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="attrs[user_limit]" value="{{ course.attrs['user_limit'] }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button id="sale-submit" class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>