{% extends 'templates/main.volt' %}

{% block content %}

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
            <tr>
                <td>{{ item.id }}</td>
                <td>
                    <span><a href="{{ url({'for':'admin.chapter.edit','id':item.id}) }}">{{ item.title }}</a></span>
                    <span class="layui-badge layui-bg-green">课</span>
                </td>
                <td><span class="layui-badge layui-bg-gray">{{ item.attrs['word_count'] }}</span></td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ url({'for':'admin.chapter.update','id':item.id}) }}"></td>
                <td><input type="checkbox" name="free" value="1" lay-skin="switch" lay-text="是|否" lay-filter="free" data-url="{{ url({'for':'admin.chapter.update','id':item.id}) }}" {% if item.free == 1 %}checked{% endif %}></td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ url({'for':'admin.chapter.update','id':item.id}) }}" {% if item.published == 1 %}checked{% endif %}></td>
                <td align="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                        <ul>
                            <li><a href="{{ url({'for':'admin.chapter.edit','id':item.id}) }}">编辑</a></li>
                            <li><a href="javascript:" class="kg-delete" data-url="{{ url({'for':'admin.chapter.delete','id':item.id}) }}">删除</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}