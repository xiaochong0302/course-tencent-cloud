{%- macro file_status(value) %}
    {% if value == 'pending' %}
        <span class="layui-badge layui-bg-gray">待上传</span>
    {% elseif value == 'uploaded' %}
        <span class="layui-badge layui-bg-black">已上传</span>
    {% elseif value == 'translating' %}
        <span class="layui-badge layui-bg-blue">转码中</span>
    {% elseif value == 'translated' %}
        <span class="layui-badge layui-bg-green">已转码</span>
    {% elseif value == 'failed' %}
        <span class="layui-badge layui-bg-red">已失败</span>
    {% endif %}
{%- endmacro %}

<table class="kg-table layui-table layui-form">
    <colgroup>
        <col>
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
        <th>编号</th>
        <th>名称</th>
        <th>视频状态</th>
        <th>视频时长</th>
        <th>排序</th>
        <th>免费</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in lessons %}
        <tr>
            <td>{{ item.id }}</td>
            <td>
                <span><a href="{{ url({'for':'admin.chapter.edit','id':item.id}) }}">{{ item.title }}</a></span>
                <span class="layui-badge layui-bg-green">课</span>
            </td>
            <td>{{ file_status(item.attrs.file_status) }}</td>
            <td>{{ item.attrs.duration|play_duration }}</td>
            <td><input class="layui-input kg-priority-input" type="text" name="priority" value="{{ item.priority }}" chapter-id="{{ item.id }}"></td>
            <td><input type="checkbox" name="free" value="1" lay-skin="switch" lay-text="是|否" lay-filter="switch-free" chapter-id="{{ item.id }}" {% if item.free == 1 %}checked{% endif %}></td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="switch-published" chapter-id="{{ item.id }}" {% if item.published == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.chapter.edit','id':item.id}) }}">编辑</a></li>
                        <li><a href="javascript:;" class="kg-delete" url="{{ url({'for':'admin.chapter.delete','id':item.id}) }}">删除</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>
