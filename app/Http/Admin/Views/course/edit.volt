<fieldset class="layui-elem-field layui-field-title">
    <legend>编辑课程</legend>
</fieldset>

<div class="layui-tab layui-tab-brief">

    <ul class="layui-tab-title kg-tab-title">
        <li class="layui-this">基本信息</li>
        <li>课程介绍</li>
        <li>营销设置</li>
        <li>相关课程</li>
    </ul>

    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            {{ partial('course/edit_basic') }}
        </div>
        <div class="layui-tab-item">
            {{ partial('course/edit_desc') }}
        </div>
        <div class="layui-tab-item">
            {{ partial('course/edit_sale') }}
        </div>
        <div class="layui-tab-item">
            {{ partial('course/edit_related') }}
        </div>
    </div>

</div>