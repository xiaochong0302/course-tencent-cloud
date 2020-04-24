<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a class="kg-back"><i class="layui-icon layui-icon-return"></i> 返回</a>
            {% if course %}
                <a><cite>{{ course.title }}</cite></a>
            {% endif %}
            <a><cite>咨询管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.consult.search'}) }}">
            <i class="layui-icon layui-icon-search"></i>搜索咨询
        </a>
    </div>
</div>

<table class="kg-table layui-table layui-form">
    <colgroup>
        <col>
        <col>
        <col>
        <col width="10%">
        <col width="10%">
    </colgroup>
    <thead>
    <tr>
        <th>问答</th>
        <th>用户</th>
        <th>时间</th>
        <th>发布</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>
                <p>课程：<a href="{{ url({'for':'admin.consult.list'},{'course_id':item.course.id}) }}">{{ item.course.title }}</a></p>
                <p>提问：<a href="javascript:" title="{{ item.question }}">{{ substr(item.question,0,25) }}</a></p>
                {% if item.answer %}
                    <p>回复：<a href="javascript:" title="{{ item.answer }}">{{ substr(item.answer,0,25) }}</a></p>
                {% endif %}
            </td>
            <td>
                <p>昵称：{{ item.user.name }}</p>
                <p>编号：{{ item.user.id }}</p>
            </td>
            <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
            <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ url({'for':'admin.consult.update','id':item.id}) }}" {% if item.published == 1 %}checked{% endif %}></td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span>
                    </button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.consult.edit','id':item.id}) }}">编辑</a></li>
                        {% if item.deleted == 0 %}
                            <li><a href="javascript:" class="kg-delete" data-url="{{ url({'for':'admin.consult.delete','id':item.id}) }}">删除</a></li>
                        {% else %}
                            <li><a href="javascript:" class="kg-restore" data-url="{{ url({'for':'admin.consult.restore','id':item.id}) }}">还原</a></li>
                        {% endif %}
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}