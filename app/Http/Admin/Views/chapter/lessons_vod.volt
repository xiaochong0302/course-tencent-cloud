{%- macro file_status(value) %}
    {% if value == 'pending' %}
        待上传
    {% elseif value == 'uploaded' %}
        已上传
    {% elseif value == 'translating' %}
        转码中
    {% elseif value == 'translated' %}
        已转码
    {% elseif value == 'failed' %}
        已失败
    {% endif %}
{%- endmacro %}

<table class="layui-table layui-form kg-table">
    <colgroup>
        <col>
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
        <th>学员</th>
        <th>点赞</th>
        <th>评论</th>
        <th>排序</th>
        <th>免费</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in lessons %}
        {% set chapter_url = url({'for':'home.chapter.show','id':item.id}) %}
        {% set edit_url = url({'for':'admin.chapter.edit','id':item.id}) %}
        {% set update_url = url({'for':'admin.chapter.update','id':item.id}) %}
        {% set delete_url = url({'for':'admin.chapter.delete','id':item.id}) %}
        {% set restore_url = url({'for':'admin.chapter.restore','id':item.id}) %}
        {% set comment_url = url({'for':'admin.comment.list'},{'item_id':item.id,'item_type':1}) %}
        <tr>
            <td>{{ item.id }}</td>
            <td>
                <p>
                    <a href="{{ edit_url }}">{{ item.title }}</a>
                    <span class="layui-badge layui-bg-green">课</span>
                </p>
                <p class="meta">
                    <span>状态：{{ file_status(item.attrs['file']['status']) }}</span>
                    <span>时长：{{ item.attrs['duration']|duration }}</span>
                </p>
            </td>
            <td>{{ item.user_count }}</td>
            <td>{{ item.like_count }}</td>
            <td>{{ item.comment_count }}</td>
            <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
            <td><input type="checkbox" name="free" value="1" lay-skin="switch" lay-text="是|否" lay-filter="free" data-url="{{ update_url }}" {% if item.free == 1 %}checked="checked"{% endif %}></td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
            <td class="center">
                <div class="kg-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                    <ul>
                        {% if item.published == 1 %}
                            <li><a href="{{ chapter_url }}" target="_blank">浏览</a></li>
                        {% endif %}
                        <li><a href="{{ edit_url }}">编辑</a></li>
                        {% if item.deleted == 0 %}
                            <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                        {% else %}
                            <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原</a></li>
                        {% endif %}
                        <hr>
                        <li><a href="javascript:" class="kg-comment" data-url="{{ comment_url }}">评论管理</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>