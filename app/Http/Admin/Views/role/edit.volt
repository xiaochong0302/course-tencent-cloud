<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.role.update','id':role.id}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑角色</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="name" value="{{ role.name }}" {% if role.type == 'system' %}readonly{% endif %} lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="summary" value="{{ role.summary }}" {% if role.type == 'system' %}readonly{% endif %} lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">权限</label>
        <div class="layui-input-block">
            {% for key,level in auth_nodes %}
                <table class="layui-table">
                    {% for key2,level2 in level.child %}
                        <tr>
                            {% if key2 == 0 %}
                                <td width="15%" rowspan="{{ level.child|length }}">{{ level.label }}</td>
                            {% endif %}
                            <td width="15%">{{ level2.label }}</td>
                            <td>
                                {% for level3 in level2.child %}
                                    <input type="checkbox" name="routes[]" title="{{ level3.label }}" value="{{ level3.route }}" {% if level3.route in role.routes %}checked{% endif %}>
                                {% endfor %}
                            </td>
                        </tr>
                    {% endfor %}
                </table>
            {% endfor %}
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>