{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.im_group.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索群组</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">群组编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="群组编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">群组名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" placeholder="群组名称模糊匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">群主编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="群主编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">课程编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="course_id" placeholder="课程编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="course" title="课程">
                <input type="radio" name="type" value="chat" title="聊天">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是">
                <input type="radio" name="published" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">删除</label>
            <div class="layui-input-block">
                <input type="radio" name="deleted" value="1" title="是">
                <input type="radio" name="deleted" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}