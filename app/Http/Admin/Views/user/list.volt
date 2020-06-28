{%- macro gender_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-red">男</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-green">女</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-gray">密</span>
    {% endif %}
{%- endmacro %}

{%- macro edu_role_info(user) %}
    {% if user.edu_role.id == 1 %}
        <span class="layui-badge layui-bg-gray">学员</span>
    {% elseif user.edu_role.id == 2 %}
        <span class="layui-badge layui-bg-blue">讲师</span>
    {% endif %}
{%- endmacro %}

{%- macro admin_role_info(user) %}
    {% if user.admin_role.id %}
        <span class="layui-badge layui-bg-gray">{{ user.admin_role.name }}</span>
    {% endif %}
{%- endmacro %}

{%- macro status_info(user) %}
    {% if user.vip == 1 %}
        <span class="layui-badge layui-bg-orange" title="期限：{{ date('Y-m-d H:i:s',user.vip_expiry_time) }}">会员</span>
    {% endif %}
    {% if user.locked == 1 %}
        <span class="layui-badge" title="期限：{{ date('Y-m-d H:i:s',user.lock_expiry_time) }}">锁定</span>
    {% endif %}
{%- endmacro %}

<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>用户管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.user.search'}) }}">
            <i class="layui-icon layui-icon-search"></i>搜索用户
        </a>
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.user.add'}) }}">
            <i class="layui-icon layui-icon-add-1"></i>添加用户
        </a>
    </div>
</div>

<table class="kg-table layui-table">
    <colgroup>
        <col>
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
        <th>昵称</th>
        <th>性别</th>
        <th>教学角色</th>
        <th>后台角色</th>
        <th>活跃时间</th>
        <th>注册时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>{{ item.id }}</td>
            <td><span title="{{ item.about }}">{{ item.name }}</span>{{ status_info(item) }}</td>
            <td>{{ gender_info(item.gender) }}</td>
            <td>{{ edu_role_info(item) }}</td>
            <td>{{ admin_role_info(item) }}</td>
            <td>{{ date('Y-m-d H:i:s',item.active_time) }}</td>
            <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            <td align="center">
                <div class="layui-dropdown">
                    <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                    <ul>
                        <li><a href="{{ url({'for':'admin.user.edit','id':item.id}) }}">编辑</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}