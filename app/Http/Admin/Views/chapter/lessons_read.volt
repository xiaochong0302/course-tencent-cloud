<table class="layui-table kg-table layui-form">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col width="12%">
    </colgroup>
    <thead>
    <tr>
        <th>编号</th>
        <th>名称</th>
        <th>字数</th>
        <th>排序</th>
        <th>免费</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in lessons %}
        {% set preview_url = url({'for':'home.chapter.show','id':item.id}) %}
        {% set edit_url = url({'for':'admin.chapter.edit','id':item.id}) %}
        {% set update_url = url({'for':'admin.chapter.update','id':item.id}) %}
        {% set delete_url = url({'for':'admin.chapter.delete','id':item.id}) %}
        {% set restore_url = url({'for':'admin.chapter.restore','id':item.id}) %}
        <tr>
            <td>{{ item.id }}</td>
            <td>
                <span><a href="{{ edit_url }}">{{ item.title }}</a></span>
                <span class="layui-badge layui-bg-green">课</span>
            </td>
            <td><span class="layui-badge layui-bg-gray">{{ item.attrs['word_count'] }}</span></td>
            <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
            <td><input type="checkbox" name="free" value="1" lay-skin="switch" lay-text="是|否" lay-filter="free" data-url="{{ update_url }}" {% if item.free == 1 %}checked="checked"{% endif %}></td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
            <td class="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                    <ul>
                        <li><a href="{{ preview_url }}" target="_blank">预览</a></li>
                        <li><a href="{{ edit_url }}">编辑</a></li>
                        {% if item.deleted == 0 %}
                            <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                        {% else %}
                            <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原</a></li>
                        {% endif %}
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>