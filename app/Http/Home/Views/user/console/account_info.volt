{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro connect_provider(item) %}
        {% if item.provider == 1 %}
            <i class="layui-icon layui-icon-login-qq login-qq"></i>
        {% elseif item.provider == 2 %}
            <i class="layui-icon layui-icon-login-wechat login-wechat"></i>
        {% elseif item.provider == 3 %}
            <i class="layui-icon layui-icon-login-weibo login-weibo"></i>
        {% endif %}
    {%- endmacro %}

    {%- macro connect_user(item) %}
        {% if item.open_avatar %}
            <span class="open-avatar"><img src="{{ item.open_avatar }}"></span>
        {% endif %}
        <span class="open-name">{{ item.open_name }}</span>
    {%- endmacro %}

    {% set edit_pwd_url = url({'for':'home.uc.account'},{'type':'password'}) %}
    {% set edit_phone_url = url({'for':'home.uc.account'},{'type':'phone'}) %}
    {% set edit_email_url = url({'for':'home.uc.account'},{'type':'email'}) %}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">账号安全</span>
                </div>
                <div class="security-item-list">
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                        <span class="title">登录密码</span>
                        <span class="summary">经常更改密码有助于保护您的帐号安全</span>
                        <span class="action"><a class="layui-btn layui-btn-sm btn-edit-pwd" href="{{ edit_pwd_url }}">修改</a></span>
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-cellphone"></i></span>
                        <span class="title">手机绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定手机：{{ account.phone|anonymous }}</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-phone" href="{{ edit_phone_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-phone" href="{{ edit_phone_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                        <span class="title">邮箱绑定</span>
                        {% if account.email %}
                            <span class="summary">已绑定邮箱：{{ account.email|anonymous }}</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-email" href="{{ edit_email_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-email" href="{{ edit_email_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                </div>
                <div class="my-nav">
                    <span class="title">开放登录</span>
                </div>
                {% if connects %}
                    <div class="connect-tips">已经绑定的第三方帐号</div>
                    <div class="connect-list">
                        <table class="layui-table">
                            <tr>
                                <td>序号</td>
                                <td>提供方</td>
                                <td>用户信息</td>
                                <td>创建日期</td>
                                <td width="15%">操作</td>
                            </tr>
                            {% for connect in connects %}
                                {% set url = url({'for':'home.uc.unconnect','id':connect.id}) %}
                                <tr>
                                    <td>{{ loop.index }}</td>
                                    <td>{{ connect_provider(connect) }}</td>
                                    <td>{{ connect_user(connect) }}</td>
                                    <td>{{ date('Y-m-d H:i',connect.create_time) }}</td>
                                    <td><a class="layui-btn layui-btn-danger layui-btn-sm kg-delete" href="javascript:" data-url="{{ url }}" data-tips="确定要解除绑定吗？">解除绑定</a></td>
                                </tr>
                            {% endfor %}
                        </table>
                    </div>
                {% endif %}
                <div class="connect-tips">支持绑定的第三方帐号</div>
                <div class="oauth-list">
                    {% if oauth_provider.qq.enabled == 1 %}
                        <a class="layui-icon layui-icon-login-qq login-qq" href="{{ url({'for':'home.oauth.qq'}) }}"></a>
                    {% endif %}
                    {% if oauth_provider.weixin.enabled == 1 %}
                        <a class="layui-icon layui-icon-login-wechat login-wechat" href="{{ url({'for':'home.oauth.weixin'}) }}"></a>
                    {% endif %}
                    {% if oauth_provider.weibo.enabled == 1 %}
                        <a class="layui-icon layui-icon-login-weibo login-weibo" href="{{ url({'for':'home.oauth.weibo'}) }}"></a>
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

{% endblock %}