{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vip'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>会员设置</legend>
        </fieldset>
        <table class="layui-table layui-form kg-table" style="width:80%;">
            <colgroup>
                <col width="20%">
                <col width="20%">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>启用</th>
                <th>期限</th>
                <th>价格（元）</th>
            </tr>
            </thead>
            <tbody>
            {% for item in items %}
                <tr>
                    <td>
                        <input type="radio" name="vip[{{ item.id }}][deleted]" value="0" title="是" {% if item.deleted == 0 %}checked="checked"{% endif %}>
                        <input type="radio" name="vip[{{ item.id }}][deleted]" value="1" title="否" {% if item.deleted == 1 %}checked="checked"{% endif %}>
                    </td>
                    <td>
                        <select name="vip[{{ item.id }}][expiry]" lay-verify="required">
                            {% for value in 1..60 %}
                                <option value="{{ value }}" {% if item.expiry == value %}selected="selected"{% endif %}>{{ value }}个月</option>
                            {% endfor %}
                        </select>
                    </td>
                    <td><input class="layui-input" type="text" name="vip[{{ item.id }}][price]" value="{{ item.price }}" lay-verify="number"></td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
        <br>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}