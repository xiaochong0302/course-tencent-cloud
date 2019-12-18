{%- macro role_info(user) %}
    {% if user.edu_role.id > 0 %}
        <span class="layui-badge layui-bg-green">{{ user.edu_role.name }}</span>
    {% endif %}
    {% if user.admin_role.id > 0 %}
        <span class="layui-badge layui-bg-blue">{{ user.admin_role.name }}</span>
    {% endif %}
{%- endmacro %}

{%- macro status_info(user) %}
    {% if user.locked == 0 %}
        <span class="layui-badge layui-bg-green">正常</span>
    {% else %}
        <span class="layui-badge" title="期限：{{ date('Y-m-d H:i:s',user.locked_expiry) }}">锁定</span>
    {% endif %}
{%- endmacro %}

{%- macro vip_info(user) %}
    {% if user.vip == 1 %}
        <span class="layui-badge layui-badge-sm layui-bg-orange" title="期限：{{ date('Y-m-d H:i:s',user.vip_expiry) }}">vip</span>
    {% endif %}
{%- endmacro %}

<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a class="kg-back"><i class="layui-icon layui-icon-return"></i> 返回</a>
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
        <col width="12%">
    </colgroup>
    <thead>
    <tr>
        <th>编号</th>
        <th>用户</th>
        <th>角色</th>
        <th>注册日期</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>{{ item.id }}</td>
            <td><a href="javascript:" title="{{ item.about }}">{{ item.name }}</a>{{ vip_info(item) }}</td>
            <td>{{ role_info(item) }}</td>
            <td>{{ date('Y-m-d',item.created_at) }}</td>
            <td>{{ status_info(item) }}</td>
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